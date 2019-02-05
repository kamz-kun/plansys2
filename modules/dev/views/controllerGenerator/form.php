<div ng-controller="PageController" ng-cloak>
    <div ui-layout class="sub" options="{ flow : 'column' }">
        <div ui-layout-container size='60%' min-size="300px">
            <div ui-header>
                <?php echo $controller; ?><br> 
            </div>
            <div ui-content>
                <table class="table table-responsive" style="background:#f6f6f6;">
                    <tr>
                        <td><b>Source Code</b></td>
                    </tr>
                    <tr>
                        <td><span class="code" style="word-wrap:break-word;"><?php echo ControllerGenerator::controllerPath($class, $type); ?></span></td>
                    </tr>
                </table>
                <div ui-header>
                    <div class="btn btn-xs btn-success pull-right" style="margin-top:4px;" ng-click="new ()">
                        <i class="fa fa-plus"></i>
                        New 
                    </div>
                    Action List
                </div>
                <div class="action-list">    
                    <table class="table-responsive table table-bordered">
                        <thead>
                            <tr colspan=2 style="background:#f6f6f6;">
                                <th>Action Name</th><th colspan="2">Parameter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in list">
                                <td style="width:50%;">{{ item.name}}</td>
                                <td style="width:50%;border-right-color:#ffffff;">
                                    <div class="badge">    
                                        {{item.param}}
                                    </div>
                                    <?php
                                    $controllerName = lcfirst(substr($controller, 0, -10));
                                    $url = "{{getUrl('" . $module . "','" . $controllerName . "',item.name)}}";
                                    ?>
                                    <a href="<?php echo $this->createUrl("/" . $url); ?>" 
                                       target="_blank" class="pull-right btn btn-default btn-xs" 
                                       style="margin-left:5px;{{item.param != null ? 'opacity:0' :''}}">
                                        <i class="fa fa-globe"></i>
                                    </a>

                                    <a href="#" class="btn btn-default pull-right btn-xs" ng-click="update(item)">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="alert alert-warning" ng-if="list.length == 0">
                        <span>Action Empty</span>
                    </div>
                </div>
            </div>
        </div>
        <div ui-layout-container size='40%' min-size="300px">
            <div ui-header>Properties</div>
            <div ui-content style="padding:3px 20px;">
                <div ng-show="active == null">
                    <?php include("empty.php"); ?>
                </div> 
                <div ng-show="active != null" 
                     onload="isLoading = false"
                     ng-include="Yii.app.createUrl('dev/controllerGenerator/renderProperties', {
                         module: '<?= $module; ?>'
                     })"></div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    app.controller("PageController", ["$scope", "$http", "$timeout", function($scope, $http, $timeout) {
            $scope.list = <?php echo CJSON::encode($method); ?>;
            $scope.edit = false;
            $scope.isLoading = true;
            $scope.activeTree = null;
            $scope.active = null;
            $scope.saving = false;
            $scope.selecting = false;
            $scope.select = function(item) {
                if (item == null)
                    return;
                $scope.selecting = true;
                $scope.active = item;
            };
            $scope.new = function() {
                $scope.edit = true;
                $scope.active = {
                    'name': 'actionIndex',
                    'template': 'default',
                    'param': '',
                    'form': ''
                };
            };
            $scope.oldValue = null;
            $scope.update = function(item) {
                $scope.edit = false;
                $scope.oldValue = {
                    'name': item.name,
                    'param': item.param
                };
                $scope.active = angular.copy($scope.oldValue);
                $scope.active.oldName = $scope.oldValue.name;
            };
            $scope.create = function() {
                if ($scope.edit == true) {
                    var url = '<?php echo $this->createUrl("save", array('module' => $module, 'class' => $controller, 'type' => $type)); ?>';

                } else {
                    var url = '<?php echo $this->createUrl("rename", array('module' => $module, 'class' => $controller, 'type' => $type)); ?>';

                }
                $http.post(url, {list: $scope.active}).success(function(data, status) {
                    $scope.saving = false;
                    if ($scope.edit == true) {
                        $scope.list.push($scope.active);
                        $scope.edit = false;
                        $scope.active = null;
                    } else {
                        var key = 0;
                        $scope.list.forEach(function() {
                            if ($scope.list[key].name == $scope.oldValue.name) {
                                $scope.list[key].name = $scope.active.name;
                                $scope.list[key].param = $scope.active.param;
                            }
                            key++;
                        });
                        $scope.oldValue = null;
                        $scope.active = null;
                    }
                    $scope.select($scope.active);
                }).error(function(data, status) {
                    $scope.saving = false;
                });
            };
            $scope.getUrl = function(module, controller, action) {
                var action = action.match(/([A-Z])\w+/g);
                var url = module + '/' + controller + '/' + action;
                return url;
            };
        }
    ]);
</script>