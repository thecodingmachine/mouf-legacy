<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xj="http://xjconf.net/XJConf"
                xmlns:stub="http://stubbles.net/websites">

  <xsl:output method="xml" />
  <!-- 
  <xsl:output method="xml" indent="yes"/>
  <xsl:strip-space elements="stub:defaultResolver"/> 
  -->

  <xsl:param name="choosenProcs" />
  <xsl:param name="defaultProc" />

  <xsl:template match="node()|@*">
    <xsl:copy>
      <xsl:apply-templates select="node()|@*"/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="xj:configuration/stub:defaultResolver/@default">
      <xsl:attribute name="default"><xsl:value-of select="$defaultProc"/></xsl:attribute>
  </xsl:template>

  <xsl:template match="xj:configuration/stub:defaultResolver/stub:processor">
      <xsl:variable name="currentProc" select="@name"/>
      <xsl:if test="contains($choosenProcs, $currentProc)">
          <xsl:element name="processor" namespace="http://stubbles.net/websites">
              <xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
              <xsl:attribute name="type"><xsl:value-of select="@type"/></xsl:attribute>
              <xsl:if test="@interceptorDescriptor">
                <xsl:attribute name="interceptorDescriptor"><xsl:value-of select="@interceptorDescriptor"/></xsl:attribute>
              </xsl:if>
              <xsl:if test="@pageFactoryClass">
                <xsl:attribute name="pageFactoryClass"><xsl:value-of select="@pageFactoryClass"/></xsl:attribute>
              </xsl:if>
          </xsl:element>
      </xsl:if>
  </xsl:template>

</xsl:stylesheet>