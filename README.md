# Codeigniter-Database-Driven-Route
Simple model to generate cache of URL from database for faster performance.

Insert this code in your `application/config/config.php`

    # Static Pages Route dynamicly generated
    if ( file_exists( APPPATH . 'cache/routes.php' ) )
        include_once APPPATH . 'cache/routes.php';

Trigger method where you're dealing with slug edit e.g Blog Post, Product Edit, Add etc.

    $this->load->model('RouteModel');
    $this->RouteModel->rewriteURIs();

Any suggestion/query will be appreciated!

Thanks.
