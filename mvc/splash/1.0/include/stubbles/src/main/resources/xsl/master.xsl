<xsl:stylesheet version="1.1"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:ixsl="http://www.w3.org/1999/XSL/TransformOutputAlias"
    xmlns:xi="http://www.w3.org/2001/XInclude"
    xmlns:php="http://php.net/xsl"
    xmlns:stub="http://stubbles.net/stub"
    exclude-result-prefixes="php xi ixsl stub">
		
  <xsl:import href="copy.xsl"/>
  <xsl:import href="stub.xsl"/>
  <xsl:import href="variant.xsl"/>
  <xsl:import href="ingrid.xsl"/>
  <xsl:namespace-alias stylesheet-prefix="ixsl" result-prefix="xsl"/>

  <xsl:param name="__path" select="@path"/>
  <xsl:param name="page" select="@page"/>
  <xsl:param name="lang" select="@lang"/>
  <xsl:param name="lang_base" select="@lang_base"/>

  <xsl:template match="/">
    <xsl:apply-templates select="node()"/>
  </xsl:template>

</xsl:stylesheet>