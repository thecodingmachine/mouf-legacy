<?php
/**
 * An exception thrown if 2 rows are returned when TDBM_Service->getObject is called.
 * This can only happen if you use a filter bag as second parameter to the getObject method.
 *
 */
class TDBM_DuplicateRowException extends TDBM_Exception {


}
?>