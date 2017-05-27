exports.init = function(app) {
    app.all('/*', function(req, res) {
        var URI_path = req.params[0];
        URI_path = URI_path.split('/');
        var result = 'Error case';
        switch (URI_path[0]) {
            case '':
                {
                    result = "Start case";
                    break;
                }
            case 'admin':
                {
                    result = "Admin case";
                    break;
                }
            case 'user':
                {
                    result = "User case";
                    break;
                }
        }
        res.send(result);
    });
};