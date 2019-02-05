
$scope.toggleParams = function() {
    alert(JSON.stringify($scope.params.prm, null, 2))
}
$scope.pdf= false;

function renderPDF(url, canvasContainer, options) {
    var options = options || { scale: 1 };
        
    function renderPage(page) {
        var viewport = page.getViewport(options.scale);
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var renderContext = {
          canvasContext: ctx,
          viewport: viewport
        };
        
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        canvasContainer.appendChild(canvas);
        
        page.render(renderContext);
    }
    
    function renderPages(pdfDoc) {
        for(var num = 1; num <= pdfDoc.numPages; num++)
            pdfDoc.getPage(num).then(renderPage);
    }
    PDFJS.disableWorker = true;
    PDFJS.getDocument(url).then(renderPages);
}   

var intval = setInterval(function() {
    var name = $scope.params.prm._name;
    var rid = $scope.params.prm._rid;
    var url = Yii.app.createUrl('/sys/jasper/check&name='+name+'&rid=' + rid);
    $http.get(url).success(function (data) {
        if (data == "ok") {
            clearInterval(intval);
            $scope.pdf = true;
            renderPDF($scope.params.pdfurl, document.getElementById('the-canvas'));
        }
    });
}, 2000);