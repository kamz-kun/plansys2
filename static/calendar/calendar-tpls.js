angular.module("ui.rCalendar.tpls", ["app/static/angular-calendar/calendar.html","app/static/angular-calendar/day.html","app/static/angular-calendar/month.html","app/static/angular-calendar/week.html"]);
angular.module('ui.rCalendar', ['ui.rCalendar.tpls'])
    .constant('calendarConfig', {
        formatDay: 'dd',
        formatDayHeader: 'EEE',
        formatDayTitle: 'MMMM dd, yyyy',
        formatWeekTitle: 'MMMM yyyy, Week w',
        formatMonthTitle: 'MMMM yyyy',
        formatWeekViewDayHeader: 'EEE d',
        formatHourColumn: 'ha',
        calendarMode: 'month',
        showWeeks: false,
        showEventDetail: false,
        startingDay: 0,
        allDayLabel: 'all day',
        noEventsLabel: 'No Events',
        eventSource: null,
        queryMode: 'local',
        step: 60
    })
    .controller('ui.rCalendar.CalendarController', ['$scope', '$attrs', '$parse', '$interpolate', '$log', 'dateFilter', 'calendarConfig', function ($scope, $attrs, $parse, $interpolate, $log, dateFilter, calendarConfig) {
        'use strict';
        var self = this,
            ngModelCtrl = {$setViewValue: angular.noop}; // nullModelCtrl;

        // Configuration attributes
        angular.forEach(['formatDay', 'formatDayHeader', 'formatDayTitle', 'formatWeekTitle', 'formatMonthTitle', 'formatWeekViewDayHeader', 'formatHourColumn',
            'allDayLabel', 'noEventsLabel'], function (key, index) {
            self[key] = angular.isDefined($attrs[key]) ? $interpolate($attrs[key])($scope.$parent) : calendarConfig[key];
        });

        angular.forEach(['showWeeks', 'showEventDetail', 'startingDay', 'eventSource', 'queryMode', 'step'], function (key, index) {
            self[key] = angular.isDefined($attrs[key]) ? ($scope.$parent.$eval($attrs[key])) : calendarConfig[key];
        });

        self.hourParts = 1;
        if (self.step === 60 || self.step === 30 || self.step === 15) {
            self.hourParts = Math.floor(60 / self.step);
        } else {
            throw new Error('Invalid step parameter: ' + self.step);
        }

        var unregisterFn = $scope.$parent.$watch($attrs.eventSource, function (value) {
            self.onEventSourceChanged(value);
        });

        $scope.$on('$destroy', unregisterFn);

        $scope.calendarMode = $scope.calendarMode || calendarConfig.calendarMode;
        if (angular.isDefined($attrs.initDate)) {
            self.currentCalendarDate = $scope.$parent.$eval($attrs.initDate);
        }
        if (!self.currentCalendarDate) {
            self.currentCalendarDate = new Date();
            if ($attrs.ngModel && !$scope.$parent.$eval($attrs.ngModel)) {
                $parse($attrs.ngModel).assign($scope.$parent, self.currentCalendarDate);
            }
        }

        self.init = function (ngModelCtrl_) {
            ngModelCtrl = ngModelCtrl_;

            ngModelCtrl.$render = function () {
                self.render();
            };
        };

        self.render = function () {
            if (ngModelCtrl.$modelValue) {
                var date = new Date(ngModelCtrl.$modelValue),
                    isValid = !isNaN(date);

                if (isValid) {
                    this.currentCalendarDate = date;
                } else {
                    $log.error('"ng-model" value must be a Date object, a number of milliseconds since 01.01.1970 or a string representing an RFC2822 or ISO 8601 date.');
                }
                ngModelCtrl.$setValidity('date', isValid);
            }
            this.refreshView();
        };

        self.refreshView = function () {
            if (this.mode) {
                this.range = this._getRange(this.currentCalendarDate);
                this._refreshView();
                this.rangeChanged();
            }
        };

        // Split array into smaller arrays
        self.split = function (arr, size) {
            var arrays = [];
            while (arr.length > 0) {
                arrays.push(arr.splice(0, size));
            }
            return arrays;
        };

        self.onEventSourceChanged = function (value) {
            self.eventSource = value;
            if (self._onDataLoaded) {
                self._onDataLoaded();
            }
        };

        $scope.move = function (direction) {
            var step = self.mode.step,
                currentCalendarDate = self.currentCalendarDate,
                year = currentCalendarDate.getFullYear() + direction * (step.years || 0),
                month = currentCalendarDate.getMonth() + direction * (step.months || 0),
                date = currentCalendarDate.getDate() + direction * (step.days || 0),
                firstDayInNextMonth;

            currentCalendarDate.setFullYear(year, month, date);
            if ($scope.calendarMode === 'month') {
                firstDayInNextMonth = new Date(year, month + 1, 1);
                if (firstDayInNextMonth.getTime() <= currentCalendarDate.getTime()) {
                    self.currentCalendarDate = new Date(firstDayInNextMonth - 24 * 60 * 60 * 1000);
                }
            }
            ngModelCtrl.$setViewValue(self.currentCalendarDate);
            self.refreshView();
        };

        self.move = function (direction) {
            $scope.move(direction);
        };

        self.rangeChanged = function () {
            if (self.queryMode === 'local') {
                if (self.eventSource && self._onDataLoaded) {
                    self._onDataLoaded();
                }
            } else if (self.queryMode === 'remote') {
                if ($scope.rangeChanged) {
                    $scope.rangeChanged({
                        startTime: this.range.startTime,
                        endTime: this.range.endTime
                    });
                }
            }
        };

        function overlap(event1, event2) {
            var earlyEvent = event1,
                lateEvent = event2;
            if (event1.startIndex > event2.startIndex || (event1.startIndex === event2.startIndex && event1.startOffset > event2.startOffset)) {
                earlyEvent = event2;
                lateEvent = event1;
            }

            if (earlyEvent.endIndex <= lateEvent.startIndex) {
                return false;
            } else {
                return !(earlyEvent.endIndex - lateEvent.startIndex === 1 && earlyEvent.endOffset + lateEvent.startOffset >= self.hourParts);
            }
        }

        function calculatePosition(events) {
            var i,
                j,
                len = events.length,
                maxColumn = 0,
                col,
                isForbidden = new Array(len);

            for (i = 0; i < len; i += 1) {
                for (col = 0; col < maxColumn; col += 1) {
                    isForbidden[col] = false;
                }
                for (j = 0; j < i; j += 1) {
                    if (overlap(events[i], events[j])) {
                        isForbidden[events[j].position] = true;
                    }
                }
                for (col = 0; col < maxColumn; col += 1) {
                    if (!isForbidden[col]) {
                        break;
                    }
                }
                if (col < maxColumn) {
                    events[i].position = col;
                } else {
                    events[i].position = maxColumn++;
                }
            }
        }

        function calculateWidth(orderedEvents, hourParts) {
            var totalSize = 24 * hourParts,
                cells = new Array(totalSize),
                event,
                index,
                i,
                j,
                len,
                eventCountInCell,
                currentEventInCell;

            //sort by position in descending order, the right most columns should be calculated first
            orderedEvents.sort(function (eventA, eventB) {
                return eventB.position - eventA.position;
            });
            for (i = 0; i < totalSize; i += 1) {
                cells[i] = {
                    calculated: false,
                    events: []
                };
            }
            len = orderedEvents.length;
            for (i = 0; i < len; i += 1) {
                event = orderedEvents[i];
                index = event.startIndex * hourParts + event.startOffset;
                while (index < event.endIndex * hourParts - event.endOffset) {
                    cells[index].events.push(event);
                    index += 1;
                }
            }

            i = 0;
            while (i < len) {
                event = orderedEvents[i];
                if (!event.overlapNumber) {
                    var overlapNumber = event.position + 1;
                    event.overlapNumber = overlapNumber;
                    var eventQueue = [event];
                    while ((event = eventQueue.shift())) {
                        index = event.startIndex * hourParts + event.startOffset;
                        while (index < event.endIndex * hourParts - event.endOffset) {
                            if (!cells[index].calculated) {
                                cells[index].calculated = true;
                                if (cells[index].events) {
                                    eventCountInCell = cells[index].events.length;
                                    for (j = 0; j < eventCountInCell; j += 1) {
                                        currentEventInCell = cells[index].events[j];
                                        if (!currentEventInCell.overlapNumber) {
                                            currentEventInCell.overlapNumber = overlapNumber;
                                            eventQueue.push(currentEventInCell);
                                        }
                                    }
                                }
                            }
                            index += 1;
                        }
                    }
                }
                i += 1;
            }
        }

        self.placeEvents = function (orderedEvents) {
            calculatePosition(orderedEvents);
            calculateWidth(orderedEvents, self.hourParts);
        };

        self.placeAllDayEvents = function (orderedEvents) {
            calculatePosition(orderedEvents);
        };
    }])
    .directive('calendar', function () {
        'use strict';
        return {
            restrict: 'EA',
            replace: true,
            templateUrl: 'app/static/angular-calendar/calendar.html',
            scope: {
                calendarMode: '=',
                rangeChanged: '&',
                eventSelected: '&',
                timeSelected: '&',
                showDetails: '&'
            },
            require: ['calendar', '?^ngModel'],
            controller: 'ui.rCalendar.CalendarController',
            link: function (scope, element, attrs, ctrls) {
                var calendarCtrl = ctrls[0], ngModelCtrl = ctrls[1];

                if (ngModelCtrl) {
                    calendarCtrl.init(ngModelCtrl);
                }

                scope.$on('changeDate', function (event, direction) {
                    calendarCtrl.move(direction);
                });

                scope.$on('eventSourceChanged', function (event, value) {
                    calendarCtrl.onEventSourceChanged(value);
                });
            }
        };
    })
    .directive('monthview', ['dateFilter', function (dateFilter) {
        'use strict';
        return {
            restrict: 'EA',
            replace: true,
            templateUrl: 'app/static/angular-calendar/month.html',
            require: ['^calendar', '?^ngModel'],
            link: function (scope, element, attrs, ctrls) {
                var ctrl = ctrls[0],
                    ngModelCtrl = ctrls[1];
                scope.showWeeks = ctrl.showWeeks;
                scope.showEventDetail = ctrl.showEventDetail;
                scope.noEventsLabel = ctrl.noEventsLabel;
                scope.allDayLabel = ctrl.allDayLabel;
                // scope.cEvent = [];

                ctrl.mode = {
                    step: {months: 1}
                };

                function getDates(startDate, n) {
                    var dates = new Array(n), current = new Date(startDate), i = 0;
                    current.setHours(12); // Prevent repeated dates because of timezone bug
                    while (i < n) {
                        dates[i++] = new Date(current);
                        current.setDate(current.getDate() + 1);
                    }
                    return dates;
                }

                scope.clickShow = function(data){
                    scope.showDetails({
                        dataCuaca: data
                    });
                }

                scope.select = function (viewDate) {
                    // var dt = new Date(viewDate.date);
                    // var tgl = (dt.getFullYear() + '-' + String(dt.getMonth() + 1).padStart(2, '0') + '-' + String(dt.getDate()).padStart(2, '0'));
                    var rows = scope.rows;
                    var selectedDate = viewDate.date;
                    var events = viewDate.events;
                    if (rows) {
                        var currentCalendarDate = ctrl.currentCalendarDate;
                        var currentMonth = currentCalendarDate.getMonth();
                        var currentYear = currentCalendarDate.getFullYear();
                        var selectedMonth = selectedDate.getMonth();
                        var selectedYear = selectedDate.getFullYear();
                        var direction = 0;
                        if (currentYear === selectedYear) {
                            if (currentMonth !== selectedMonth) {
                                direction = currentMonth < selectedMonth ? 1 : -1;
                            }
                        } else {
                            direction = currentYear < selectedYear ? 1 : -1;
                        }

                        ctrl.currentCalendarDate = selectedDate;
                        if (ngModelCtrl) {
                            ngModelCtrl.$setViewValue(selectedDate);
                        }
                        if (direction === 0) {
                            for (var row = 0; row < 6; row += 1) {
                                for (var date = 0; date < 7; date += 1) {
                                    var selected = ctrl.compare(selectedDate, rows[row][date].date) === 0;
                                    rows[row][date].selected = selected;
                                    if (selected) {
                                        scope.selectedDate = rows[row][date];
                                    }
                                }
                            }
                        } else {
                            ctrl.refreshView();
                        }

                        if (scope.timeSelected) {
                            if(events !== undefined){
                                scope.cEvent = events;
                                scope.showEventDetail = true;
                                scope.timeSelected({
                                    selectedTime: selectedDate,
                                    events: events
                                });
                            }else{
                                scope.showEventDetail = false;
                            }
                        }
                    }
                };

                ctrl._refreshView = function () {
                    var startDate = ctrl.range.startTime,
                        date = startDate.getDate(),
                        month = (startDate.getMonth() + (date !== 1 ? 1 : 0)) % 12,
                        year = startDate.getFullYear() + (date !== 1 && month === 0 ? 1 : 0);

                    var days = getDates(startDate, 42);
                    for (var i = 0; i < 42; i++) {
                        days[i] = angular.extend(createDateObject(days[i], ctrl.formatDay), {
                            secondary: days[i].getMonth() !== month
                        });
                    }

                    scope.labels = new Array(7);
                    for (var j = 0; j < 7; j++) {
                        scope.labels[j] = dateFilter(days[j].date, ctrl.formatDayHeader);
                    }

                    var headerDate = new Date(year, month, 1);
                    scope.$parent.title = dateFilter(headerDate, ctrl.formatMonthTitle);
                    scope.rows = ctrl.split(days, 7);

                    if (scope.showWeeks) {
                        scope.weekNumbers = [];
                        var thursdayIndex = (4 + 7 - ctrl.startingDay) % 7,
                            numWeeks = scope.rows.length;
                        for (var curWeek = 0; curWeek < numWeeks; curWeek++) {
                            scope.weekNumbers.push(
                                getISO8601WeekNumber(scope.rows[curWeek][thursdayIndex].date));
                        }
                    }
                };

                function createDateObject(date, format) {
                    return {
                        date: date,
                        label: dateFilter(date, format),
                        selected: ctrl.compare(date, ctrl.currentCalendarDate) === 0,
                        current: ctrl.compare(date, new Date()) === 0
                    };
                }

                function compareEvent(event1, event2) {
                    if (event1.allDay) {
                        return 1;
                    } else if (event2.allDay) {
                        return -1;
                    } else {
                        return (event1.startTime.getTime() - event2.startTime.getTime());
                    }
                }

                ctrl._onDataLoaded = function () {
                    var eventSource = ctrl.eventSource,
                        len = eventSource ? eventSource.length : 0,
                        startTime = ctrl.range.startTime,
                        endTime = ctrl.range.endTime,
                        utcStartTime = new Date(Date.UTC(startTime.getFullYear(), startTime.getMonth(), startTime.getDate())),
                        utcEndTime = new Date(Date.UTC(endTime.getFullYear(), endTime.getMonth(), endTime.getDate())),
                        rows = scope.rows,
                        oneDay = 86400000,
                        eps = 0.001,
                        row,
                        date,
                        hasEvent = false,hasHujan = false,hasGerimis = false;


                    if (rows.hasEvent) {
                        for (row = 0; row < 6; row += 1) {
                            for (date = 0; date < 7; date += 1) {
                                if (rows[row][date].hasEvent) {
                                    rows[row][date].events = null;
                                    rows[row][date].hasEvent = false;
                                    rows[row][date].hasHujan = false;
                                    rows[row][date].hasGerimis = false;
                                }
                            }
                        }
                    }

                    // for (var i = 0; i < len; i += 1) {
                    //     var event = eventSource[i];
                    //     var tgl = date;
                    // }

                    for (var i = 0; i < len; i += 1) {
                        var event = eventSource[i];
                        var eventStartTime = new Date(event.startTime);
                        var eventEndTime = new Date(event.endTime);
                        var st;
                        var et;
                        var hujan = false, gerimis = false;

                        if (event.allDay) {
                            if (eventEndTime <= utcStartTime || eventStartTime >= utcEndTime) {
                                continue;
                            } else {
                                st = utcStartTime;
                                et = utcEndTime;
                            }
                        } else {
                            if (eventEndTime <= startTime || eventStartTime >= endTime) {
                                continue;
                            } else {
                                st = startTime;
                                et = endTime;
                            }
                        }

                        var timeDiff;
                        var timeDifferenceStart;
                        if (eventStartTime <= st) {
                            timeDifferenceStart = 0;
                        } else {
                            timeDiff = eventStartTime - st;
                            if (!event.allDay) {
                                timeDiff = timeDiff - (eventStartTime.getTimezoneOffset() - st.getTimezoneOffset()) * 60000;
                            }
                            timeDifferenceStart = timeDiff / oneDay;
                        }

                        var timeDifferenceEnd;
                        if (eventEndTime >= et) {
                            timeDiff = et - st;
                            if (!event.allDay) {
                                timeDiff = timeDiff - (et.getTimezoneOffset() - st.getTimezoneOffset()) * 60000;
                            }
                            timeDifferenceEnd = timeDiff / oneDay;
                        } else {
                            timeDiff = eventEndTime - st;
                            if (!event.allDay) {
                                timeDiff = timeDiff - (eventEndTime.getTimezoneOffset() - st.getTimezoneOffset()) * 60000;
                            }
                            timeDifferenceEnd = timeDiff / oneDay;
                        }

                        if(event.cuaca == 'Hujan'){
                            hujan = true;
                        }else if(event.cuaca == 'Gerimis'){
                            gerimis = true;
                        }

                        var index = Math.floor(timeDifferenceStart);
                        var eventSet;
                        while (index < timeDifferenceEnd - eps) {
                            var rowIndex = Math.floor(index / 7);
                            var dayIndex = Math.floor(index % 7);                            
                            // rows[rowIndex][dayIndex].hasEvent = true;
                            rows[rowIndex][dayIndex].hasHujan = hujan;
                            rows[rowIndex][dayIndex].hasGerimis = gerimis;
                            eventSet = rows[rowIndex][dayIndex].events;
                            if (eventSet) {
                                eventSet.push({summary: event.summary, details: event.details, tgl_lang: event.tgl_lang});
                            } else {
                                eventSet = [];
                                eventSet.push({summary: event.summary, details: event.details, tgl_lang: event.tgl_lang});
                                rows[rowIndex][dayIndex].events = eventSet;
                            }
                            index += 1;
                        }
                    }

                    for (row = 0; row < 6; row += 1) {
                        for (date = 0; date < 7; date += 1) {
                            var avail = false;
                            if (rows[row][date].hasEvent) {
                                hasEvent = true;
                                // rows[row][date].events.sort(compareEvent);
                            }
                            if (rows[row][date].hasHujan) {
                                hasHujan = true;
                            }
                            if (rows[row][date].hasGerimis) {
                                hasGerimis = true;
                            }

                            if(avail){
                            }
                        }
                    }
                    rows.hasEvent = hasEvent;
                    rows.hasHujan = hasHujan;
                    rows.hasGerimis = hasGerimis;
                    
                    var findSelected = false;
                    for (row = 0; row < 6; row += 1) {
                        for (date = 0; date < 7; date += 1) {
                            if (rows[row][date].selected) {
                                scope.selectedDate = rows[row][date];
                                findSelected = true;
                                break;
                            }
                        }
                        if (findSelected) {
                            break;
                        }
                    }

                };

                ctrl.compare = function (date1, date2) {
                    return (new Date(date1.getFullYear(), date1.getMonth(), date1.getDate()) - new Date(date2.getFullYear(), date2.getMonth(), date2.getDate()) );
                };

                ctrl._getRange = function getRange(currentDate) {
                    var year = currentDate.getFullYear(),
                        month = currentDate.getMonth(),
                        firstDayOfMonth = new Date(year, month, 1),
                        difference = ctrl.startingDay - firstDayOfMonth.getDay(),
                        numDisplayedFromPreviousMonth = (difference > 0) ? 7 - difference : -difference,
                        startDate = new Date(firstDayOfMonth),
                        endDate;

                    if (numDisplayedFromPreviousMonth > 0) {
                        startDate.setDate(-numDisplayedFromPreviousMonth + 1);
                    }

                    endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + 42);

                    return {
                        startTime: startDate,
                        endTime: endDate
                    };
                };

                function getISO8601WeekNumber(date) {
                    var dayOfWeekOnFirst = (new Date(date.getFullYear(), 0, 1)).getDay();
                    var firstThurs = new Date(date.getFullYear(), 0, ((dayOfWeekOnFirst <= 4) ? 5 : 12) - dayOfWeekOnFirst);
                    var thisThurs = new Date(date.getFullYear(), date.getMonth(), date.getDate() + (4 - date.getDay()));
                    var diff = thisThurs - firstThurs;
                    return (1 + Math.round(diff / 6.048e8)); // 6.048e8 ms per week
                }

                ctrl.refreshView();
            }
        };
    }]);

angular.module("app/static/angular-calendar/calendar.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("app/static/angular-calendar/calendar.html",
    "<div ng-switch=\"calendarMode\">\n" +
    "    <div class=\"row calendar-navbar\">\n" +
    "        <div class=\"nav-left col-xs-2\">\n" +
    "            <button type=\"button\" class=\"btn btn-default btn-sm\" ng-click=\"move(-1)\"><i\n" +
    "                    class=\"glyphicon glyphicon-chevron-left\"></i></button>\n" +
    "        </div>\n" +
    "        <div class=\"calendar-header col-xs-8\">{{title}}</div>\n" +
    "        <div class=\"nav-right col-xs-2\">\n" +
    "            <button type=\"button\" class=\"btn btn-default btn-sm\" ng-click=\"move(1)\"><i\n" +
    "                    class=\"glyphicon glyphicon-chevron-right\"></i></button>\n" +
    "        </div>\n" +
    "    </div>\n" +
    "    <dayview ng-switch-when=\"day\"></dayview>\n" +
    "    <monthview ng-switch-when=\"month\"></monthview>\n" +
    "    <weekview ng-switch-when=\"week\"></weekview>\n" +
    "</div>\n" +
    "");
}]);

angular.module("app/static/angular-calendar/month.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("app/static/angular-calendar/month.html",
    "<div>\n" +
    "    <table class=\"table table-bordered table-fixed monthview-datetable monthview-datetable\">\n" +
    "        <thead>\n" +
    "        <tr>\n" +
    "            <th ng-show=\"showWeeks\" class=\"calendar-week-column text-center\">#</th>\n" +
    "            <th ng-repeat=\"label in labels track by $index\" class=\"text-center\">\n" +
    "                <small>{{label}}</small>\n" +
    "            </th>\n" +
    "        </tr>\n" +
    "        </thead>\n" +
    "        <tbody>\n" +
    "        <tr ng-repeat=\"row in rows track by $index\">\n" +
    "            <td ng-show=\"showWeeks\" class=\"calendar-week-column text-center\">\n" +
    "                <small><em>{{ weekNumbers[$index] }}</em></small>\n" +
    "            </td>\n" +
    "            <td ng-repeat=\"dt in row track by dt.date\" class=\"monthview-dateCell\" ng-click=\"select(dt)\"\n" +
    "                ng-class=\"{'text-center':true, 'monthview-current': dt.current&&!dt.selected&&!dt.hasEvent,'monthview-primary-with-event':!dt.secondary&&dt.hasEvent&&!dt.selected, 'monthview-primary-with-hujan':!dt.secondary&&dt.hasHujan&&!dt.selected, 'monthview-primary-with-gerimis':!dt.secondary&&dt.hasGerimis&&!dt.selected, 'monthview-selected': dt.selected}\">\n" +
    "                <div ng-class=\"{'text-muted':dt.secondary}\">\n" +
    "                    {{dt.label}}\n" +
    "                </div>\n" +
    "            </td>\n" +
    "        </tr>\n" +
    "        </tbody>\n" +
    "    </table>\n" +
    "    <div class='row legend legend-sub'>\n"+
        "    <div class='col-md-6'>\n" +
        "       <div class='col-md-1 legend-today'></div>\n" +
        "       <div class='col-md-3'>Today</div>\n" +
        "       <div class='col-md-1 legend-gerimis'></div>\n" +
        "       <div class='col-md-3'>Gerimis</div>\n" +
        "       <div class='col-md-1 legend-hujan'></div>\n" +
        "       <div class='col-md-3'>Hujan</div>\n" +
        "    </div>\n" +
    "    </div>\n" +
    "    <div ng-if=\"showEventDetail\" class=\"event-detail-container\">\n" +
    "        <div class=\"event-tgl\">\n" +
    "           <span>{{selectedDate.events[0].tgl_lang}}</span>\n" +
    "        </div>\n" +
    "        <br>\n" +
    "        <div class=\"row event-cuaca\" >\n" +
    "           <div class=\"col-md-1 text-center\" ng-repeat=\"event in selectedDate.events[0].summary\" ng-if=\"selectedDate.events[0].summary\">\n" +
    "               <img src=\"app/static/img/weather/sun.svg\" style=\"height:50px;\" ng-if=\"event.nm_cuaca=='Cerah'\">\n" +
    "               <img src=\"app/static/img/weather/rain.svg\" style=\"height:50px;\" ng-if=\"event.nm_cuaca=='Gerimis'\">\n" +
    "               <img src=\"app/static/img/weather/h_rain.svg\" style=\"height:50px;\" ng-if=\"event.nm_cuaca=='Hujan'\">\n" +
    "               <div><strong>{{event.nm_cuaca}}</strong></div>\n" +
    "               <div class=\"total-jam\">{{event.jml}} <span>Jam</span></div>\n" +
    "           </div>\n" +
    "        </div>\n" +
    "        <div class=\"row event-btn\">\n" +
    "           <div class=\"col-md-12 text-center\"><br><button ng-click=\"clickShow(selectedDate.events[0].details)\" class=\"btn btn-primary\"><i class=\"fa fa-search\"></i>&nbsp;Lihat Detil</button></div>\n" +
    "        </div>\n" +
    "    </div>\n" +
    "</div>");
}]);

angular.module("app/static/angular-calendar/day.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("app/static/angular-calendar/day.html",
    "<div>\n" +
    "    <div class=\"dayview-allday-table\">\n" +
    "        <div class=\"dayview-allday-label\">\n" +
    "            {{allDayLabel}}\n" +
    "        </div>\n" +
    "        <div class=\"dayview-allday-content-wrapper\">\n" +
    "            <table class=\"table table-bordered dayview-allday-content-table\">\n" +
    "                <tbody>\n" +
    "                <tr>\n" +
    "                    <td class=\"calendar-cell\" ng-class=\"{'calendar-event-wrap':allDayEvents}\"\n" +
    "                        ng-style=\"{height: 25*allDayEvents.length+'px'}\">\n" +
    "                        <div ng-repeat=\"displayEvent in allDayEvents\" class=\"calendar-event\"\n" +
    "                             ng-click=\"eventSelected({event:displayEvent.event})\"\n" +
    "                             ng-style=\"{top: 25*$index+'px',width: '100%',height:'25px'}\">\n" +
    "                            <div class=\"calendar-event-inner\">{{displayEvent.event.title}}</div>\n" +
    "                        </div>\n" +
    "                    </td>\n" +
    "                    <td ng-if=\"allDayEventGutterWidth>0\" class=\"gutter-column\"\n" +
    "                        ng-style=\"{width:allDayEventGutterWidth+'px'}\"></td>\n" +
    "                </tr>\n" +
    "                </tbody>\n" +
    "            </table>\n" +
    "        </div>\n" +
    "    </div>\n" +
    "    <div class=\"scrollable\" style=\"height: 400px\">\n" +
    "        <table class=\"table table-bordered table-fixed\">\n" +
    "            <tbody>\n" +
    "            <tr ng-repeat=\"tm in rows track by $index\">\n" +
    "                <td class=\"calendar-hour-column text-center\">\n" +
    "                    {{tm.time | date: formatHourColumn}}\n" +
    "                </td>\n" +
    "                <td class=\"calendar-cell\" ng-click=\"select(tm.time, tm.events)\">\n" +
    "                    <div ng-class=\"{'calendar-event-wrap': tm.events}\" ng-if=\"tm.events\">\n" +
    "                        <div ng-repeat=\"displayEvent in tm.events\" class=\"calendar-event\"\n" +
    "                             ng-click=\"eventSelected({event:displayEvent.event})\"\n" +
    "                             ng-style=\"{top: (37*displayEvent.startOffset/hourParts)+'px', left: 100/displayEvent.overlapNumber*displayEvent.position+'%', width: 100/displayEvent.overlapNumber+'%', height: 37*(displayEvent.endIndex -displayEvent.startIndex - (displayEvent.endOffset + displayEvent.startOffset)/hourParts)+'px'}\">                            <div class=\"calendar-event-inner\">{{displayEvent.event.title}}</div>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "                </td>\n" +
    "            </tr>\n" +
    "            </tbody>\n" +
    "        </table>\n" +
    "    </div>\n" +
    "</div>");
}]);

angular.module("app/static/angular-calendar/week.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("app/static/angular-calendar/week.html",
    "<div>\n" +
    "    <table class=\"table table-bordered table-fixed weekview-header\">\n" +
    "        <thead>\n" +
    "        <tr>\n" +
    "            <th class=\"calendar-hour-column\"></th>\n" +
    "            <th ng-repeat=\"dt in dates\" class=\"text-center weekview-header-label\">{{dt.date| date:\n" +
    "                formatWeekViewDayHeader}}\n" +
    "            </th>\n" +
    "            <th ng-if=\"gutterWidth>0\" class=\"gutter-column\" ng-style=\"{width: gutterWidth+'px'}\"></th>\n" +
    "        </tr>\n" +
    "        </thead>\n" +
    "    </table>\n" +
    "    <div class=\"weekview-allday-table\">\n" +
    "        <div class=\"weekview-allday-label\">\n" +
    "            {{allDayLabel}}\n" +
    "        </div>\n" +
    "        <div class=\"weekview-allday-content-wrapper\">\n" +
    "            <table class=\"table table-bordered table-fixed weekview-allday-content-table\">\n" +
    "                <tbody>\n" +
    "                <tr>\n" +
    "                    <td ng-repeat=\"day in dates track by day.date\" class=\"calendar-cell\">\n" +
    "                        <div ng-class=\"{'calendar-event-wrap': day.events}\" ng-if=\"day.events\"\n" +
    "                             ng-style=\"{height: 25*day.events.length+'px'}\">\n" +
    "                            <div ng-repeat=\"displayEvent in day.events\" class=\"calendar-event\"\n" +
    "                                 ng-click=\"eventSelected({event:displayEvent.event})\"\n" +
    "                                 ng-style=\"{top: 25*displayEvent.position+'px', width: 100*(displayEvent.endIndex-displayEvent.startIndex)+'%', height: '25px'}\">\n" +
    "                                <div class=\"calendar-event-inner\">{{displayEvent.event.title}}</div>\n" +
    "                            </div>\n" +
    "                        </div>\n" +
    "                    </td>\n" +
    "                    <td ng-if=\"allDayEventGutterWidth>0\" class=\"gutter-column\"\n" +
    "                        ng-style=\"{width: allDayEventGutterWidth+'px'}\"></td>\n" +
    "                </tr>\n" +
    "                </tbody>\n" +
    "            </table>\n" +
    "        </div>\n" +
    "    </div>\n" +
    "    <div class=\"scrollable\" style=\"height: 400px\">\n" +
    "        <table class=\"table table-bordered table-fixed\">\n" +
    "            <tbody>\n" +
    "            <tr ng-repeat=\"row in rows track by $index\">\n" +
    "                <td class=\"calendar-hour-column text-center\">\n" +
    "                    {{row[0].time | date: formatHourColumn}}\n" +
    "                </td>\n" +
    "                <td ng-repeat=\"tm in row track by tm.time\" class=\"calendar-cell\" ng-click=\"select(tm.time, tm.events)\">\n" +
    "                    <div ng-class=\"{'calendar-event-wrap': tm.events}\" ng-if=\"tm.events\">\n" +
    "                        <div ng-repeat=\"displayEvent in tm.events\" class=\"calendar-event\"\n" +
    "                             ng-click=\"eventSelected({event:displayEvent.event})\"\n" +
    "                             ng-style=\"{top: (37*displayEvent.startOffset/hourParts)+'px',left: 100/displayEvent.overlapNumber*displayEvent.position+'%', width: 100/displayEvent.overlapNumber+'%', height: 37*(displayEvent.endIndex -displayEvent.startIndex - (displayEvent.endOffset + displayEvent.startOffset)/hourParts)+'px'}\">                            <div class=\"calendar-event-inner\">{{displayEvent.event.title}}</div>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "                </td>\n" +
    "                <td ng-if=\"normalGutterWidth>0\" class=\"gutter-column\" ng-style=\"{width: normalGutterWidth+'px'}\"></td>\n" +
    "            </tr>\n" +
    "            </tbody>\n" +
    "        </table>\n" +
    "    </div>\n" +
    "</div>");
}]);
