app.directive('textArea', function($timeout) {
    return {
        require: '?ngModel',
        scope: true,
        compile: function(element, attrs, transclude) {
            if (attrs.ngModel && !attrs.ngDelay) {
                attrs.$set('ngModel', '$parent.' + attrs.ngModel, false);
            }

            return  function($scope, $el, attrs, ctrl) {
                // when ng-model is changed from inside directive
                $scope.update = function() {
                    if (!!ctrl) {
                        $timeout(function() {
                            ctrl.$setViewValue($scope.value);
                        }, 0);
                    }
                };

                // when ng-model is changed from outside directive
                if (!!ctrl) {
                    ctrl.$render = function() {
                        if ($scope.inEditor && !$scope.$parent.fieldMatch($scope))
                            return;

                        if (typeof ctrl.$viewValue != "undefined") {
                            $scope.value = ctrl.$viewValue;
                            $scope.update();
                        }
                    };
                }

                // set default value
                $scope.value = $el.find("data[name=value]").html();
                $scope.name = $el.find("data[name=name]:eq(0)").html().trim();
                $scope.modelClass = $el.find("data[name=model_class]").html();
               
                //if ngModel is present, use that instead of value from php
                if (attrs.ngModel) {
                    $timeout(function() {
                        var ngModelValue = $scope.$eval(attrs.ngModel);
                        if (typeof ngModelValue != "undefined") {
                            $scope.value = ngModelValue;
                        }
                    }, 0);
                }
            }
        }
    };
});