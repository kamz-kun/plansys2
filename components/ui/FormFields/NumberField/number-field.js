app.directive('numberField', function ($timeout, $http) {
    return {
        require: '?ngModel',
        scope: true,
        compile: function (element, attrs, transclude) {
            if (attrs.ngModel && !attrs.ngDelay) {
                attrs.$set('ngModel', '$parent.' + attrs.ngModel, false);
            }

            return function ($scope, $el, attrs, ctrl) {
                // when ng-model is changed from inside directive
                $scope.update = function () {
                    if (!!ctrl) {
                        $timeout(function () {
							$scope.value = $scope.onlynumber($scope.valueshow);
							if($scope.usecommas=='y')
								$scope.valueshow = $scope.numberWithCommas($scope.value);
							else 
								$scope.valueshow = $scope.value;
                            ctrl.$setViewValue($scope.value);
                        }, 0);
                    }
                };

                // when ng-model is changed from outside directive
                if (!!ctrl) {
                    ctrl.$render = function () {
                        if ($scope.inEditor && !$scope.$parent.fieldMatch($scope))
                            return;

                        if (typeof ctrl.$viewValue != "undefined") {
                            $scope.value = ctrl.$viewValue;
                            $scope.update();
                        }
                    };
                }

                $scope.renderFormList = function () {
                    $scope.list = [];
                    for (key in $scope.originalList) {
                        if (angular.isObject($scope.originalList[key])) {
                            var subItem = [];
                            var rawSub = $scope.originalList[key];

                            for (subkey in rawSub) {
                                subItem.push({key: subkey, value: rawSub[subkey]});
                            }
                            $scope.list.push({key: key, value: subItem});
                        } else {
                            $scope.list.push({key: key, value: $scope.originalList[key]});
                        }
                    }
                }

                // set default value
                var keytimeout = null;
                $scope.name = $el.find("data[name=name]:eq(0)").html().trim();
                $scope.value = $el.find("data[name=value]").html().trim();
				$scope.valueshow = $el.find("data[name=valueshow]").html().trim();
                $scope.usecommas = $el.find("data[name=usecommas]").html().trim();
                $scope.maxValue = $el.find("data[name=maxValue]").html().trim();
				$scope.minValue = $el.find("data[name=minValue]").html().trim();
                $scope.modelClass = $el.find("data[name=model_class]").html();
                $scope.relModelClass = $el.find("data[name=rel_model_class]").html();
                $scope.acMode = $el.find("data[name=ac_mode]").html();
                $scope.paramValue = {};
                $scope.showDropdown = false;
                $scope.ngDisabled = $el.find("data[name=ng_disabled]:eq(0)").html();
                $scope.numberFieldDisabled = false;
                if (!!$scope.ngDisabled) {
                    $scope.$watch($scope.ngDisabled, function(n, o) {
                        $scope.numberFieldDisabled = n;
                    }, true)
                };
                
                for (i in $scope.params) {
                    var p = $scope.params[i];
                    if (p != null && !!p.indexOf && p.indexOf('js:') === 0) {
                        var value = $scope.$parent.$eval(p.replace('js:', ''));
                        var key = i;

                        $scope.$parent.$watch(p.replace('js:', ''), function (newv, oldv) {
                            if (newv != oldv) {
                                for (i in $scope.params) {
                                    var x = $scope.params[i];
                                    if (x == p) {
                                        $scope.paramValue[i] = newv;
                                    }
                                }
                                $scope.doSearchRelation();
                            }
                        }, true);

                        $scope.paramValue[key] = value;
                        $scope.doSearchRelation();
                    }
                }
                
                $scope.isFocused = false;
                $scope.tfFocus = function() {
                    if (!$scope.isFocused && !$scope.dropdownHover) {
                        if ($scope.acMode == "comma" && !!$scope.value) {
                            var val = $scope.value.trim();
                            if (val[val.length -1] != "," && val.length > 0) {
                                $scope.value = $scope.value + ", ";
                            }
                            $scope.search = "";
                            $scope.doSearch();
                        }
                        $timeout(function() {
                            $scope.showDropDown = true;
                        });
                    }
                    $scope.isFocused = true;
                }
                
                $scope.tfBlur = function() {
                    $scope.dropdownHover = false;
                    $timeout(function() {
                        if (!$scope.isFocused && !$scope.dropdownHover && !!$scope.value && !!$scope.value.trim) {
                            var val = $scope.value.trim();
                            if (val[val.length -1] == ",") {
                                $scope.value = val.substr(0, val.length -1);
                            }
                        }
                        
                        if (!$scope.dropdownHover) {
                            $scope.showDropDown = false;
                        }
                    }, 200);
                    $scope.isFocused = false;

                    
					if($scope.minValue!=''){
						var chk = parseFloat($scope.onlynumber($scope.valueshow))
						if(chk<parseFloat($scope.minValue)){
							$scope.value = $scope.onlynumber($scope.minValue)
						} else {
							$scope.value = $scope.onlynumber($scope.valueshow);	
						}
					} else {
						$scope.value = $scope.onlynumber($scope.valueshow);
					}
					
					if($scope.maxValue!=''){
						var chk = parseFloat($scope.onlynumber($scope.valueshow))
						if(chk>parseFloat($scope.maxValue)){
							$scope.value = $scope.onlynumber($scope.maxValue)
						} else {
							$scope.value = $scope.onlynumber($scope.valueshow);	
						}
					} else {
						$scope.value = $scope.onlynumber($scope.valueshow);
					}
					
					if($scope.value==""){
						$scope.value = 0;
					}
							
					if($scope.usecommas=='y')
						$scope.valueshow = $scope.numberWithCommas($scope.value);
					else 
						$scope.valueshow = $scope.value;
					ctrl.$setViewValue($scope.value);
					
                }                   
                
                // if ngModel is present, use that instead of value from php
                if (attrs.ngModel) {
                    $timeout(function () {
                        var ngModelValue = $scope.$eval(attrs.ngModel);
                        if (typeof ngModelValue != "undefined") {
                            $scope.value = ngModelValue;                        
                            $scope.valueshow = $scope.numberWithCommas(ngModelValue);
                            $scope.update();
                        }
                    }, 0);
                }
				
				//get only number
				$scope.onlynumber = function(r){                    
					if(r!=''){
                        if(r.charAt(0) == '0'){
                            r = r.substring(1)
                        }
						return r.replace(/[^0-9.]/g, "");
					} else if(r=='0' || r=='') {                        
                        return 0;
                    }
				}
				
				//use commas
				$scope.numberWithCommas = function(x) {
					if(x === undefined || x === null){
                        x = 0;
                    }
					return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				}

            }
        }
    };
});