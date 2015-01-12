<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A Simple and efficient model for database driven routing for project
 *
 * @author      :   Ehsaan Khatree
 * @category    :   URL Routing
 * @version     :   v1.00
 * @package     :   CodeIgniter
 * @subpackage  :   Model
 */
class RouteModel extends CI_Model {
    /**
     * Table name where URIs are saved
     */
    private $tableName = 'ko_pages';
    
    /**
     * Column name of table where URIs are saved
     * If value of this variable found empty then uri will rewrite by title column name
     */
    private $URIcolumnName = 'page_uri';

    /**
     * Function to generate map to string
     * parser (:column_name)
     * @example: 'staticpage/(:id)'
     */
    private $URIMapTo = 'shop/staticpage/(:page_id)';

    /**
     * Works only if $URIcolumnName is empty, will generate possible slug and writes cache file;
     */
    private $titleColumnName = 'page_name';

    /* Temporary variable to get access for current row data */
    private $_tmpArray = array();

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
    }

    /**
     * Function to return generated value of slug defined auto-handled
     *
     * @return: string|void
     */
    private function getAssociateSlugValue( $value='' ) {
        if ( !is_array($value) ) {
            show_error( 'Database row result must be in array! ' . gettype($value) . ' given!' );
            return;
        }

        if ( $this->URIcolumnName != '' )
            return $value[ $this->URIcolumnName ];

        /* else generate from title */
        return url_title( $value[ $this->titleColumnName ], '-', TRUE );

    }

    /**
     * Function to return generated value of slug defined auto-handled
     *
     * @return: void|string
     */
    private function getAssociateMapToValue( $value='' ) {
        if ( !is_array($value) ) {
            show_error( 'Database row result must be in array! ' . gettype($value) . ' given!' );
            return;
        }

        $this->_tmpArray = $value;

        $value = preg_replace_callback('%\(:(.+?)\)%', array($this, "valueParser"), $this->URIMapTo);

        return $value;

    }

    /**
     * Get its actual value from array
     *
     * @return: string
    */
    private function valueParser($v) {
        if ( isset( $this->_tmpArray[ $v[1] ] ) )
            return $this->_tmpArray[ $v[1] ];

        return '';
    }

    /**
     * Writes contents of database table to a cache file.
     *
     * @return boolean
     */
    public function rewriteURIs()
    {
        $this->load->helper('file');

        $data = array();
        $data[] = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";

        $records = $this->SqlModel->getRecords('*',$this->tableName);

        foreach($records as $value) 
        {
            $data[] = '$route["' . $this->getAssociateSlugValue( $value ) . '"] = "' . $this->getAssociateMapToValue( $value ) . '";';
        }

        $output = implode("\n", $data);

        if ( write_file(APPPATH . "cache/routes.php", $output) )
            return true;

        return false;
    } 



}

/* End of file routemodel.php */
/* Location: ./application/models/routemodel.php */
