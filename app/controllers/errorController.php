<?php

function show404()
{
    http_response_code(404);
    require RACINE . '/app/views/layouts/header.php';
    require RACINE . '/app/views/errorView.php';
    require RACINE . '/app/views/layouts/footer.php';
}
