$scope.status = 'Ready';
$scope.save = function() {
    $scope.status = 'Saving...';
    var data = {
        content: $scope.model.css_content,
    };
    $http.post(Yii.app.createUrl('/dev/setting/saveCustomCss'), data)
        .success(function(data) {
            $scope.status = "Saved";
        })
        .error(function(data) {
            $scope.status = "Save Failed!"
        });
}

window.$(document).keydown(function(event) {
    $scope.status = navigator.platform.indexOf('Mac') > -1 ? 'Save: Cmd + S' : 'Save: Ctrl + S';
    if ((!(String.fromCharCode(event.which).toLowerCase() == 's' && (event.metaKey || event.ctrlKey)) &&
            !(event.which === 13 && (event.metaKey || event.ctrlKey))
        ) && !(event.which == 19)) return true;

    if (String.fromCharCode(event.which).toLowerCase() == 's') {
        $scope.save();
    }

    event.preventDefault();
    return false;
});

var editor = ace.edit("css_content");
editor.session.setMode("ace/mode/css");