<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="./dist/swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="./dist/index.css" />
    <link rel="icon" type="image/png" href="./dist/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./dist/favicon-16x16.png" sizes="16x16" />
</head>

<body>
    <div id="swagger-ui"></div>
    <script src="./dist/swagger-ui-bundle.js" charset="UTF-8"> </script>
    <script src="./dist/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
    <script src="./dist/swagger-initializer.js" charset="UTF-8"> </script>
    <script>
        window.onload = function () {
            //<editor-fold desc="Changeable Configuration Block">

            // the following lines will be replaced by docker/configurator, when it runs in a docker-container
            window.ui = SwaggerUIBundle({
                // url: "http://localhost:8080/doc/doc.php",
                url: "/doc/doc.php",
                // url: "/docs/openapi.yaml",
                dom_id: '#swagger-ui',
                schemes: 'http',
                deepLinking: true,
                // docExpansion: "close",
                docExpansion: "list",
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset,
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                requestInterceptor: (req) => {
                    if (req.loadSpec) {
                        let hash = "Tetsts bbb";
                        req.headers.Authorization = "Bearer " + hash;
                    }
                    // console.log("Teste \n");
                    // console.log(req);
                    // console.log(' === ');

                    return req;
                }
                // layout: "BaseLayout"
            });

            //</editor-fold>
        };
    </script>
</body>

</html>