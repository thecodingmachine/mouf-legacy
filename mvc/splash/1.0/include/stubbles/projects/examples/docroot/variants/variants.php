<?php
/**
 * Example for variants
 *
 * @author  Frank Kleine <mikey@stubbles.net>
 * @author  Stephan Schmidt <schst@stubbles.net>
 * @see     http://www.stubbles.net/wiki/Docs/MVC/Variant
 */
require '../bootstrap-stubbles.php';

stubClassLoader::load('net::stubbles::websites::variantmanager::stubVariantXJConfFactory',
                      'net::stubbles::ipo::request::stubWebRequest',
                      'net::stubbles::ipo::response::stubBaseResponse',
                      'net::stubbles::ipo::session::stubPHPSession'
);
class Bootstrap
{
    public static function main()
    {
        $factory = new stubVariantXJConfFactory(stubConfig::getConfigPath() . '/xml/variantmanager.xml');
        $request = new stubWebRequest();
        $session = new stubPHPSession($request, new stubBaseResponse(), 'web');
        echo '<pre>';
        var_dump($factory->getVariantsMap()->getVariant($session, $request)->getFullQualifiedName());
        echo '</pre>';
    }
}
Bootstrap::main();
?>