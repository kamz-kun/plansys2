app.controller("PageController", function ($scope, $http, $localStorage, $timeout) {
    $scope.list = actionFormList;
    $scope.active = null;

    $scope.menuSelect = null;
    $scope.getIcon = function (item) {
        return 'fa-file-text-o ';
    }

    $scope.getType = function (sel) {
        if (typeof sel.count != 'undefined') {
            return "module";
        }

        if (!sel.class) {
            return "dir";
        }

        return "form";
    };
    $scope.delForm = function (sel, item) {
        $http.get(Yii.app.createUrl('/dev/forms/delForm', {
            p: item.alias,
        })).success(function (data) {
            if (!!data) {
                alert(data);
            } else {
                sel.remove();
            }
        });
    };
    $scope.delFolder = function (sel, item) {
        $http.get(Yii.app.createUrl('/dev/forms/delFolder', {
            p: item.alias,
        })).success(function (data) {
            if (!!data) {
                alert(data);
            } else {
                sel.remove();
            }
        });
    };
    $scope.addForm = function (classname, extendsname, item) {

        if (!!classname && !!extendsname) {
            if (item.alias[item.alias.length - 1] == '.') {
                item.alias = item.alias.slice(0, -1);
            }
            var module = item.module.replace('Plansys: ', '');
            var params = {
                c: classname,
                e: extendsname,
                p: item.alias,
                m: module
            };
            $http.get(Yii.app.createUrl('/dev/forms/addForm', params)).success(function (data) {
                if (data) {
                    if (data.success) {
                        var sp = item.alias.split(".");
                        var shortName = data.class + "";
                        if (sp[1] == 'modules') {
                            sp = sp.splice(2);
                            if (shortName.toLowerCase().indexOf(sp[0].toLowerCase()) === 0) {
                                shortName = shortName.substr(sp[0].length);
                                sp.shift();
                            }
                            
                            sp = sp.splice(1);
                            for (var s in sp) {
                                if (shortName.toLowerCase().indexOf(sp[s].toLowerCase()) === 0) {
                                    shortName = shortName.substr(sp[s].length);
                                }
                            }
                        }
                        
                        item.items.push({
                            name: data.class,
                            shortName: shortName,
                            class: data.class,
                            module: item.module,
                            alias: item.alias + "." + data.class,
                            items: []
                        });
                        $scope.active = item.items[item.items.length - 1];
                        window.open(Yii.app.createUrl('dev/forms/update', {
                            'class': item.alias + "." + data.class
                        }), 'iframe');
                    } else {
                        alert(data.error);
                    }
                    return;
                }
            })
        }
    };

    $scope.addFolder = function (foldername, item) {
        if (!!foldername) {
            $http.get(Yii.app.createUrl('/dev/forms/addFolder', {
                n: foldername.toLowerCase(),
                p: item.alias
            })).success(function (data) {
                if (data) {
                    if (data.success) {
                        item.items.push({
                            alias: data.alias,
                            name: data.name,
                            module: item.module,
                            id: data.id,
                            items: []
                        });
                    } else {
                        alert(data.error);
                    }
                    return;
                }
            })
        }
    };
    $scope.executeMenu = function (e, func) {
        if (typeof func == "function") {
            $timeout(function () {
                func($scope.menuSelect);
            });
        }
    }
    window.activeScope = $scope;
    $scope.formTreeOpen = function (sel, e, item) {
        $scope.menuSelect = sel.$modelValue;
        $(".menu-sel").removeClass("active").removeClass(".menu-sel");
        $(e.target).parent().addClass("menu-sel active");
        var type = $scope.getType(sel.$modelValue);
        switch (type) {
            case "module":
                $scope.formTreeMenu = [
                    {
                        icon: "fa fa-fw fa-file-text-o",
                        label: "New Form",
                        click: function (item) {
                            $scope.activeItem = item;
                            PopupCenter(Yii.app.createUrl('/dev/forms/newForm'), "Create New Form", '400', '500');
                        }
                    },
                    {
                        icon: "fa fa-fw fa-folder-o",
                        label: "New Folder",
                        click: function (item) {
                            var foldername = prompt("Enter new folder name:");
                            $scope.addFolder(foldername, item);
                        }
                    },
                    {
                        hr: true
                    },
                    {
                        icon: "fa fa-fw fa-cube",
                        label: "New CRUD",
                        click: function (item) {
                            $scope.activeItem = item;
                            PopupCenter(Yii.app.createUrl('/dev/crud/new'), "Create New CRUD", '800', '550');
                        }
                    },
                ];
                $timeout(function () {
                    $scope.select(sel, item);
                    sel.expand();
                });
                break;
            case "dir":
                $scope.formTreeMenu = [
                    {
                        icon: "fa fa-fw fa-file-text-o",
                        label: "New Form",
                        click: function (item) {
                            $scope.activeItem = item;
                            PopupCenter(Yii.app.createUrl('/dev/forms/newForm'), "Create New Form", '400', '500');
                        }
                    },
                    {
                        icon: "fa fa-fw fa-folder-o",
                        label: "New Folder",
                        click: function (item) {
                            var foldername = prompt("Enter new folder name:");
                            $scope.addFolder(foldername, item);
                        }
                    },
                    {
                        hr: true
                    },
                    {
                        icon: "fa fa-fw fa-cube",
                        label: "New CRUD",
                        click: function (item) {
                            $scope.activeItem = item;
                            PopupCenter(Yii.app.createUrl('/dev/crud/new'), "Create New CRUD", '800', '550');
                        }
                    },
                    {
                        hr: true
                    },
                    {
                        icon: "fa fa-fw fa-pencil",
                        label: "Rename",
                        click: function (item) {
                            var newname = prompt("Enter new name:");
                        }
                    },
                    {
                        icon: "fa fa-fw fa-sign-in",
                        label: "Move To",
                        click: function (item) {
                            alert("This feature is stil under construction...");
                        }
                    },
                    {
                        hr: true
                    },
                    {
                        icon: "fa fa-fw  fa-trash",
                        label: "Delete",
                        click: function (item) {
                            if (confirm("Are you sure want to delete this item ?")) {
                                $scope.delFolder(sel, item);
                            }
                        }
                    }
                ];
                $timeout(function () {
                    $scope.select(sel, item);
                    sel.expand();
                });
                break;
            case "form":
                $scope.formTreeMenu = [
                    {
                        icon: "fa fa-fw fa-sign-in",
                        label: "Open New Tab",
                        click: function (item) {
                            window.open(
                                    Yii.app.createUrl('/dev/forms/update', {
                                        'class': item.alias
                                    }),
                                    '_blank'
                                    );
                        }
                    },
                    {
                        icon: "fa fa-fw fa-pencil",
                        label: "Edit Code",
                        click: function (item) {
                            window.open(
                                    Yii.app.createUrl('/dev/forms/code', {
                                        'c': item.alias
                                    }),
                                    'iframe'
                                    );
                        }
                    },
                    {
                        hr: true
                    },
                    //{
                    //    icon: "fa fa-fw fa-pencil",
                    //    label: "Rename",
                    //    click: function (item) {
                    //        var newname = prompt("Enter new form name:");
                    //    }
                    //},
                    //{
                    //    icon: "fa fa-fw fa-sign-in",
                    //    label: "Move To",
                    //    click: function (item) {
                    //        alert("This feature is stil under construction...");
                    //    }
                    //},
                    //{
                    //    hr: true
                    //},
                    {
                        icon: "fa fa-fw  fa-trash",
                        label: "Delete",
                        click: function (item) {
                            if (confirm("Are you sure want to delete this item ?")) {
                                $scope.delForm(sel, item);
                            }
                        }
                    }
                ];
                break;
        }

    };

    $scope.select = function (scope, item) {
        $(".menu-sel").removeClass("active").removeClass(".menu-sel");
        $scope.active = scope.$modelValue;

        if (!!$scope.active && $scope.getType($scope.active) == 'form') {
            $("iframe").addClass('invisible');
            $(".loading").removeClass('invisible');
            $('.loading').removeAttr('style');
        }

        if (item && item.items && item.items.length > 0 && item.items[0].name == "Loading...") {
            $http.get(Yii.app.createUrl('/dev/forms/formList', {
                m: item.module
            })).success(function (d) {
                item.items = d;

                if (typeof scope.expand == "function") {
                    scope.expand();
                }
            });
            $storage.formBuilder.selected = {
                module: item.module
            };
        }
    };
    $scope.init = false;
    $scope.isSelected = function (item) {
        var s = $storage.formBuilder.selected;
        var m = item.$modelValue;
        if (!!s && !!m && !$scope.active && m.module == s.module) {
            $scope.init = true;
            return "active";
        }

        if (item.$modelValue === $scope.active) {
            return "active";
        } else {
            return "";
        }
    };

    $scope.loading = false;
    $storage = $localStorage;
    $storage.formBuilder = $storage.formBuilder || {};

    $scope.treeOptions = {
        accept: function (sourceNodeScope, destNodesScope, destIndex) {
            return true;
        }
    };

    $timeout(function () {
        $("[ui-tree-handle].active").click();
    }, 100);

});

$(document).ready(function () {
    $('iframe').on('load', function () {
        $('iframe').removeClass('invisible');
        $('.loading').addClass('invisible');
    });
});
