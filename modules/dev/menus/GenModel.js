$scope.contextMenu = [
    {
        icon: "fa fa-fw fa-plus-square-o",
        label: "New Model",
        click: function (item, e) {
            window.activeItem = item;
            PopupCenter(Yii.app.createUrl('/dev/genModel/newModel'), "Create New Model", '400', '500');
        }
    },
    {
        icon: "fa fa-fw fa-plus-square",
        label: "Create All Model",
        click: function (item, e) {
            window.activeItem = item;
            PopupCenter(Yii.app.createUrl('/dev/genModel/newAllModel'), "Create All Model", '600', '500');
        }
    },
    //{
    //    icon: "fa fa-fw fa-edit",
    //    label: "Rename Model",
    //    visible: function (item) {
    //        return !!item.$parent;
    //    },
    //    click: function (item, e) {
    //        var name = prompt('Please enter new module name:', item.label);
    //        if (!!name) {
    //            $http.get(Yii.app.createUrl('/dev/genModel/rename', {})).success(function (status) {
    //                if (status != "SUCCESS") {
    //                    alert(status);
    //                } else {
    //                }
    //            });
    //        }
    //    }
    //},
    {
        icon: "fa fa-fw fa-sign-in",
        label: "Open New Tab",
        visible: function (item) {
            return !!item.$parent;
        },
        click: function (item) {
            window.open(item.url,'_blank');
        }
    },
    {
        hr: true,
        visible: function (item) {
            return !!item.$parent;
        }
    },
//    {
//        icon: "fa fa-fw fa-play-circle",
//        label: function(item) {
//            return item.label + " Workflow";
//        },
//        visible: function (item) {
//            return !!item.$parent;
//        },
//        click: function (item) {
//        }
//    },
//    {
//        icon: "fa fa-fw fa-unlock-alt",
//        label: function(item) {
//            return item.label + " Rules";
//        },
//        visible: function (item) {
//            return !!item.$parent;
//        },
//        click: function (item) {
//        }
//    },
//    {
//        icon: "fa fa-fw fa-upload",
//        label: function(item) {
//            return item.label + " Importer";
//        },
//        visible: function (item) {
//            return !!item.$parent;
//        },
//        click: function (item) {
//        }
//    },
//    {
//        hr: true,
//        visible: function (item) {
//            return !!item.$parent;
//        }
//    },
    {
        icon: "fa fa-fw fa-trash",
        label: "Delete Model",
        visible: function (item) {
            return !!item.$parent;
        },
        click: function (item, e) {
            if (prompt('WARNING: THIS OPERATION CANNOT BE UNDONE\n\nType "DELETE" to delete ' + item.label + ' model') == "DELETE") {
                $http.get(Yii.app.createUrl('/dev/genModel/del', {
                    p: item.class
                })).success(function (data) {
                    item.$tree.remove();
                }.bind(this));
            }
        }
    }
];