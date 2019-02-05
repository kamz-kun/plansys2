var par = $scope.params.prm;

for (var i in par) {
    var val = par[i];
    
    var arval = val.match(/\[\[\s*.+\s*\]\]/g);
    while (arval) {
        if (!Array.isArray(arval)) break;
        arval.forEach(function(text) {
            var res = prompt(text.replace("[[", "").replace("]]", ""));
            if (res !== null) {
                val = val.replace(text, res);
            }
        });
        arval = val.match(/\[\[\s*.+\s*\]\]/g);
    }
    par[i] = val;
}

par[$scope.params.csrfname] = $scope.params.csrf;

function redirectPost(url, data) {
    var form = document.createElement('form');
    document.body.appendChild(form);
    form.method = 'post';
    form.action = url;
    for (var name in data) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}

redirectPost(location.href, par);