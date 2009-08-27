<?php
/**
 * Entrace point for LDAP usage (via stubLDAPURL::fromString(urlString)).
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_ldap
 * @version     $Id: stubLDAPURLContainer.php 1933 2008-11-20 18:00:45Z mikey $
 */
stubClassLoader::load('net::stubbles::peer::stubURLContainer',
                      'net::stubbles::peer::ldap::stubLDAPConnection'
);
/**
 * Entrace point for LDAP usage (via stubLDAPURL::fromString(urlString)).
 *
 * @package     stubbles
 * @subpackage  peer_ldap
 * @see         RFC 4510  LDAP: Technical Specification Road Map              http://tools.ietf.org/html/rfc4510
 * @see         RFC 4514  LDAP: String Representation of Distinguished Names  http://tools.ietf.org/html/rfc4514
 * @see         RFC 4516  LDAP: Uniform Resource Locator                      http://tools.ietf.org/html/rfc4516
 * @see         RFC 4515  LDAP: String Representation of Search Filters       http://tools.ietf.org/html/rfc4515
 */
interface stubLDAPURLContainer extends stubURLContainer
{
    /**
     * Gets the base dn (distinguished name).
     *
     * @return  string
     */
    public function getBaseDn();
    /**
     * Changes the originally used base dn (distinguished name).
     *
     * @param  string  $newBaseDn
     */
    public function setBaseDn($newBaseDn);

    /**
     * Returns a stubLDAPConnection.
     *
     * @return  stubLDAPConnection
     */
    public function connect();
}
?>