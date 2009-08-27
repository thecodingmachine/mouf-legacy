<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:ixsl="http://www.w3.org/1999/XSL/TransformOutputAlias"
    xmlns:php="http://php.net/xsl"
    xmlns:xi="http://www.w3.org/2001/XInclude"
    xmlns:stub="http://stubbles.net/stub"
    exclude-result-prefixes="xi ixsl stub">

  <xsl:strip-space elements="stub:link"/>
  <xsl:strip-space elements="stub:argument"/>

  <xsl:template match="stub:document">
    <ixsl:stylesheet version="1.0" xmlns:php="http://php.net/xsl" exclude-result-prefixes="ixsl php">
      <ixsl:output>
        <xsl:copy-of select="@method"/>
        <xsl:copy-of select="@omit-xml-declaration"/>
        <xsl:copy-of select="@doctype-public"/>
        <xsl:copy-of select="@doctype-system"/>
      </ixsl:output>
      <ixsl:variable name="page" select="/document/@page"/>
      <ixsl:variable name="__acceptsCookies" select="/document/session/acceptsCookies/text()"/>
      <ixsl:variable name="__sessid" select="/document/session/id"/>
      <ixsl:variable name="__sessname" select="/document/session/name"/>
      <ixsl:variable name="__lang"><xsl:value-of select="$lang"/></ixsl:variable>
      <ixsl:variable name="__lang_base"><xsl:value-of select="$lang_base"/></ixsl:variable>
      <ixsl:template match="/">
        <xsl:apply-templates select="node()"/>
      </ixsl:template>
      <ixsl:template match="@*|node()">
        <ixsl:copy>
          <ixsl:apply-templates select="@*|node()"/>
        </ixsl:copy>
      </ixsl:template>
    </ixsl:stylesheet>
  </xsl:template>

  <xsl:template match="stub:comment">
    <xsl:comment><xsl:copy-of select="node()"/></xsl:comment>
  </xsl:template>

  <xsl:template match="stub:script">
    <script type="text/javascript"><xsl:copy-of select="@*"/>
      <ixsl:comment><xsl:copy-of select="node()"/>//</ixsl:comment>
    </script>
  </xsl:template>

  <xsl:template match="stub:error">
    <div class="stubTransformError">
      <xsl:copy-of select="node()"/>
    </div>
  </xsl:template>

  <xsl:template match="stub:argument"/>

  <xsl:template match="stub:link">
    <a>
    <xsl:copy-of select="@*[local-name() != 'processor' and local-name() != 'page' and local-name() != 'querystring']"/>
    <ixsl:attribute name="href">
      <xsl:choose>
        <xsl:when test="@processor">
          <xsl:text>/</xsl:text><xsl:value-of select="@processor"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>/xml</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:text>/</xsl:text>
      <xsl:choose>
        <xsl:when test="@page">
          <xsl:value-of select="@page"/>
        </xsl:when>
      </xsl:choose>
      <ixsl:if test="'false' = $__acceptsCookies">
        <xsl:text>;</xsl:text>
        <ixsl:value-of select="$__sessname"/>
        <xsl:text>=</xsl:text>
        <ixsl:value-of select="$__sessid"/>
      </ixsl:if>
      <xsl:for-each select="./stub:argument">
        <xsl:choose>
          <xsl:when test="position() = 1">
            <xsl:text>?</xsl:text>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>&amp;</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
        <xsl:value-of select="@name"/>
        <xsl:text>=</xsl:text>
        <xsl:apply-templates/>
      </xsl:for-each>
      <xsl:if test="@querystring">
        <xsl:variable name="requestParams" select="php:function('stubXSLCallback::invoke', 'request', 'getQueryString')"/>
        <xsl:value-of select="$requestParams"/>
      </xsl:if>
    </ixsl:attribute>
    <xsl:apply-templates/>
    </a>
  </xsl:template>

  <xsl:template match="stub:maincontent">
    <xsl:variable name="path">
      <xsl:choose>
        <xsl:when test="@path">
          <xsl:value-of select="@path"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text />
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="prefix">
      <xsl:choose>
        <xsl:when test="@prefix">
          <xsl:value-of select="@prefix"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>main_</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="suffix">
      <xsl:choose>
        <xsl:when test="@suffix">
          <xsl:value-of select="@suffix"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>.xml</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="part">
      <xsl:choose>
        <xsl:when test="@part">
          <xsl:value-of select="@part"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>content</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:call-template name="stub:include">
      <xsl:with-param name="href">
        <xsl:value-of select="$path"/>
        <xsl:value-of select="$prefix"/>
        <xsl:value-of select="$page"/>
        <xsl:value-of select="$suffix"/>
      </xsl:with-param>
      <xsl:with-param name="part" select="$part"/>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="stub:include" name="stub:include">
    <xsl:param name="project" select="@project"/>
    <xsl:param name="href" select="@href"/>
    <xsl:param name="part" select="@part"/>
    <xsl:variable name="xihref">
      <xsl:choose>
        <xsl:when test="$href">
          <xsl:text>xinc://</xsl:text>
          <xsl:choose>
            <xsl:when test="$project">
              <xsl:value-of select="$project"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:text>default</xsl:text>
            </xsl:otherwise>
          </xsl:choose>
          <xsl:text>/</xsl:text>
          <xsl:value-of select="$href"/>
          <xsl:text>?part=</xsl:text>
          <xsl:value-of select="$part"/>
          <xsl:text>#xpointer(/parts/part[@name='</xsl:text>
          <xsl:value-of select="$part"/>
          <xsl:text>']/node())</xsl:text>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>#xpointer(/parts/part[@name='</xsl:text>
          <xsl:value-of select="$part"/>
          <xsl:text>']/node())</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xi:include href="{$xihref}">
      <xi:fallback>
        ERROR: Could not include part <xsl:value-of select="$part"/> in file <xsl:value-of select="$href"/> from project <xsl:value-of select="$project"/>
      </xi:fallback>
    </xi:include>
  </xsl:template>

  <xsl:template match="stub:image">
    <xsl:variable name="dimensions" select="php:function('stubXSLCallback::invoke', 'image', 'getImageDimensions', @src)/child::*"/>
    <img>
      <xsl:copy-of select="@*[local-name() != 'width' and local-name() != 'height']"/>
      <xsl:attribute name="width"><xsl:value-of select="$dimensions/width"/></xsl:attribute>
      <xsl:attribute name="height"><xsl:value-of select="$dimensions/height"/></xsl:attribute>
    </img>
  </xsl:template>

  <xsl:template match="stub:form">
    <form>
      <xsl:attribute name="action">
        <xsl:text>/xml/</xsl:text>
        <xsl:choose>
          <xsl:when test="@send-to-page and not(@send-to-page = '')">
            <xsl:value-of select="@send-to-page"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="$page"/>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <xsl:attribute name="method">
        <xsl:choose>
          <xsl:when test="@method and not(@method = '')">
            <xsl:value-of select="@method"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>post</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <xsl:copy-of select="@*[local-name() != 'send-to-page' and local-name() != 'method']"/>
      <ixsl:if test="'false' = $__acceptsCookies">
        <input type="hidden">
          <ixsl:attribute name="name">
            <ixsl:value-of select="$__sessname"/>
          </ixsl:attribute>
          <ixsl:attribute name="value">
            <ixsl:value-of select="$__sessid"/>
          </ixsl:attribute>
        </input>
      </ixsl:if>
      <xsl:apply-templates select="node()"/>
    </form>
  </xsl:template>

  <xsl:template match="stub:langselect" name="stub:langselect">
    <xsl:variable name="langnodes" select="./stub:lang[@name = $lang]"/>
    <xsl:variable name="langbasenodes" select="./stub:lang[@name = $lang_base]"/>
    <xsl:variable name="defaultnodes" select="./stub:lang[@name = 'default']"/>
    <xsl:choose>
      <xsl:when test="$langnodes">
        <xsl:apply-templates select="$langnodes/node()"/>
      </xsl:when>
      <xsl:when test="$langbasenodes">
        <xsl:apply-templates select="$langbasenodes/node()"/>
      </xsl:when>
      <xsl:when test="$defaultnodes">
        <xsl:apply-templates select="$defaultnodes/node()"/>
      </xsl:when>
      <xsl:otherwise>
        <small>
          <xsl:text>[ No content for </xsl:text>
          <xsl:value-of select="$lang"/>
          <xsl:text> or </xsl:text>
          <xsl:value-of select="$lang_base"/>
          <xsl:text> - specify at least language default ]</xsl:text>
        </small>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="stub:itemframe" name="stub:itemframe">
    <div>
      <xsl:attribute name="class">
        <xsl:choose>
          <xsl:when test="@class and not(@class = '')">
            <xsl:text>itemframe </xsl:text>
            <xsl:value-of select="@class"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>itemframe</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <xsl:copy-of select="@*[local-name() != 'class']"/>
      <xsl:call-template name="stub:formerrors">
        <xsl:with-param name="itemid">
          <xsl:value-of select="@id"/>
          <xsl:text>_</xsl:text>
          <xsl:value-of select="@id"/>
        </xsl:with-param>
      </xsl:call-template>
      <ul>
      <xsl:apply-templates/>
      </ul>
    </div>
  </xsl:template>

  <xsl:template match="stub:formerrors" name="stub:formerrors">
    <xsl:param name="itemid" select="@itemid"/>
    <ixsl:if>
      <xsl:attribute name="test">
        <xsl:text>/document/request/value[@name = '</xsl:text>
        <xsl:value-of select="$itemid"/>
        <xsl:text>']/errors</xsl:text>
      </xsl:attribute>
      <ixsl:for-each>
        <xsl:attribute name="select">
          <xsl:text>/document/request/value[@name = '</xsl:text>
          <xsl:value-of select="$itemid"/>
          <xsl:text>']/errors/error</xsl:text>
        </xsl:attribute>
        <span class="form_error">
          <ixsl:choose>
            <ixsl:when>
              <xsl:attribute name="test">
                <xsl:text>messages/string[@locale = '</xsl:text>
                <xsl:value-of select="$lang"/>
                <xsl:text>']</xsl:text>
              </xsl:attribute>
              <ixsl:value-of>
                <xsl:attribute name="select">
                  <xsl:text>messages/string[@locale = '</xsl:text>
                  <xsl:value-of select="$lang"/>
                  <xsl:text>']/content</xsl:text>
                </xsl:attribute>
              </ixsl:value-of>
            </ixsl:when>
            <ixsl:when>
              <xsl:attribute name="test">
                <xsl:text>messages/string[@locale = '</xsl:text>
                <xsl:value-of select="$lang_base"/>
                <xsl:text>']</xsl:text>
              </xsl:attribute>
              <ixsl:value-of>
                <xsl:attribute name="select">
                  <xsl:text>messages/string[@locale = '</xsl:text>
                  <xsl:value-of select="$lang_base"/>
                  <xsl:text>']/content</xsl:text>
                </xsl:attribute>
              </ixsl:value-of>
            </ixsl:when>
            <ixsl:otherwise>
              <ixsl:value-of select="messages/string[@locale = 'default']/content"/>
            </ixsl:otherwise>
          </ixsl:choose>
        </span>
      </ixsl:for-each>
    </ixsl:if>
  </xsl:template>

  <xsl:template match="stub:item" name="stub:item">
    <xsl:param name="id" select="ancestor::stub:itemframe/@id"/>
    <li>
      <xsl:attribute name="id">
        <xsl:text>input_</xsl:text>
        <xsl:value-of select="$id"/>
        <xsl:text>_</xsl:text>
        <xsl:value-of select="@name"/>
      </xsl:attribute>
      <xsl:if test="not(@type = 'submit')">
        <label>
          <xsl:attribute name="for">
            <xsl:value-of select="$id"/>
            <xsl:text>_</xsl:text>
            <xsl:value-of select="@name"/>
          </xsl:attribute>
          <xsl:call-template name="stub:include">
            <xsl:with-param name="part">
              <xsl:value-of select="$id"/>
              <xsl:text>.</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:with-param>
          </xsl:call-template>
          <xsl:if test="@mandatory = 'true'"><xsl:text>*</xsl:text></xsl:if>
        </label>
      </xsl:if>
      <xsl:choose>
        <xsl:when test="@type = 'text' or @type = 'password'">
          <input>
            <xsl:attribute name="id">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:attribute name="name">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:copy-of select="@*[local-name() != 'id' and local-name() != 'name' and local-name() != 'path' and local-name() != 'mandatory']"/>
            <ixsl:choose>
              <ixsl:when>
                <xsl:attribute name="test">
                  <xsl:text>/document/forms/</xsl:text>
                  <xsl:value-of select="$id"/>
                  <xsl:text>/</xsl:text>
                  <xsl:value-of select="@name"/>
                </xsl:attribute>
                <ixsl:attribute name="value">
                  <ixsl:value-of>
                    <xsl:attribute name="select">
                      <xsl:text>/document/forms/</xsl:text>
                      <xsl:value-of select="$id"/>
                      <xsl:text>/</xsl:text>
                      <xsl:value-of select="@name"/>
                    </xsl:attribute>
                  </ixsl:value-of>
                </ixsl:attribute>
              </ixsl:when>
              <ixsl:otherwise>
                <xsl:if test="@path and @path != ''">
                  <ixsl:attribute name="value">
                    <ixsl:value-of>
                      <xsl:attribute name="select">
                        <xsl:value-of select="@path"/>
                      </xsl:attribute>
                    </ixsl:value-of>
                  </ixsl:attribute>
                </xsl:if>
              </ixsl:otherwise>
            </ixsl:choose>
          </input>
        </xsl:when>
        <xsl:when test="@type = 'dynamic'">
          <ixsl:if>
            <xsl:attribute name="test">
              <xsl:value-of select="@path"/>
            </xsl:attribute>
            <input type="text">
              <xsl:attribute name="id">
                <xsl:value-of select="$id"/>
                <xsl:text>_</xsl:text>
                <xsl:value-of select="@name"/>
              </xsl:attribute>
              <xsl:attribute name="name">
                <xsl:value-of select="$id"/>
                <xsl:text>_</xsl:text>
                <xsl:value-of select="@name"/>
              </xsl:attribute>
              <xsl:copy-of select="@*[local-name() != 'id' and local-name() != 'name' and local-name() != 'type' and local-name() != 'path' and local-name() != 'mandatory']"/>
              <ixsl:attribute name="value">
                <ixsl:value-of>
                  <xsl:attribute name="select">
                    <xsl:value-of select="@path"/>
                  </xsl:attribute>
                </ixsl:value-of>
              </ixsl:attribute>
            </input>
          </ixsl:if>
        </xsl:when>
        <xsl:when test="@type = 'select'">
          <select>
            <xsl:attribute name="id">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:attribute name="name">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:copy-of select="@*[local-name() != 'id' and local-name() != 'name' and local-name() != 'mandatory' and local-name() != 'type']"/>
            <xsl:apply-templates select="node()">
              <xsl:with-param name="id" select="@id"/>
              <xsl:with-param name="name" select="@name"/>
            </xsl:apply-templates>
          </select>
        </xsl:when>
        <xsl:when test="@type = 'textarea'">
          <textarea>
            <xsl:attribute name="id">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:attribute name="name">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:copy-of select="@*[local-name() != 'id' and local-name() != 'name' and local-name() != 'mandatory' and local-name() != 'type']"/>
            <xsl:apply-templates select="text()"/>
            <ixsl:if>
              <xsl:attribute name="test">
                <xsl:text>/document/forms/</xsl:text>
                <xsl:value-of select="$id"/>
                <xsl:text>/</xsl:text>
                <xsl:value-of select="@name"/>
              </xsl:attribute>
              <ixsl:value-of>
                <xsl:attribute name="select">
                  <xsl:text>/document/forms/</xsl:text>
                  <xsl:value-of select="$id"/>
                  <xsl:text>/</xsl:text>
                  <xsl:value-of select="@name"/>
                </xsl:attribute>
              </ixsl:value-of>
            </ixsl:if>
          </textarea>
        </xsl:when>
        <xsl:when test="@type = 'submit'">
          <input>
            <xsl:attribute name="name">
              <xsl:value-of select="$id"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:copy-of select="@*[local-name() != 'name']"/>
          </input>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>[ Unknown Item-Type! ]</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:call-template name="stub:formerrors">
        <xsl:with-param name="itemid">
          <xsl:value-of select="$id"/>
          <xsl:text>_</xsl:text>
          <xsl:value-of select="@name"/>
        </xsl:with-param>
      </xsl:call-template>
    </li>
  </xsl:template>

  <xsl:template match="stub:option" name="stub:option">
    <xsl:param name="name" select="@name"/>
    <option>
      <xsl:copy-of select="@*"/>
      <ixsl:if>
        <xsl:attribute name="test">
          <xsl:text>/document/forms/</xsl:text>
          <xsl:value-of select="ancestor::stub:itemframe/@id"/>
          <xsl:text>/</xsl:text>
          <xsl:value-of select="$name"/>
          <xsl:text> = '</xsl:text>
          <xsl:value-of select="@value"/>
          <xsl:text>'</xsl:text>
        </xsl:attribute>
        <ixsl:attribute name="selected">
           <xsl:text>selected</xsl:text>
        </ixsl:attribute>
      </ixsl:if>
      <xsl:apply-templates select="text()"/>
    </option>
  </xsl:template>

  <xsl:template match="stub:assistant" name="stub:assistant">
    <ixsl:if test="/document/mode">
      <ixsl:if test="/document/mode/name = 'DEV' or /document/mode/name = 'STAGE'">
        <div id="stageassistant">
          <a href="#" id="sa_close" onclick="stageAssistant.close(); return false;"><img src="/common/stageassistant/img/close.gif" width="15" height="15" alt="Close"/></a>
          <a href="#" id="sa_minimize" onclick="stageAssistant.minimize(); return false;"><img src="/common/stageassistant/img/minimize.gif" width="15" height="15" alt="Minimize"/></a>
          <div class="content">
            <div id="sa_ec">
              <div class="buttons">
                <a href="?showLastRequestXML=1" target="xmlSource"><img src="/common/stageassistant/img/b_domtree.gif" width="52" height="44" alt="View DOM tree"/></a>
              </div>
            </div>
            <div class="info">
              Page: <ixsl:value-of select="/document/@page"/><br/>
              Variant: <ixsl:value-of select="/document/session/variant/name/text()"/><br/>
              Variant-Alias: <ixsl:value-of select="/document/session/variant/alias/text()"/><br/>
            </div>
         </div>
        </div>
        <div id="stageassistant_min">
          <a href="#" id="sa_close_min" onclick="stageAssistant.close(); return false;"><img src="/common/stageassistant/img/close.gif" width="15" height="15" alt="Close"/></a>
          <a href="#" id="sa_open" onclick="stageAssistant.open(); return false;"><img src="/common/stageassistant/img/open.gif" width="15" height="15" alt="Open"/></a>
        </div>
        <script type="text/javascript">
            var stageAssistant;
            function onStageAssistantLoad()
            {
                stageAssistant = new stubbles.StageAssistant();
            }
        </script>
      </ixsl:if>
    </ixsl:if>
  </xsl:template>

  <xsl:template match="stub:date">
    <xsl:value-of select="php:function('stubXSLCallback::invoke', 'date', 'formatDate', @format, @timestamp)"/>
  </xsl:template>

  <xsl:template match="stub:localeDate">
    <xsl:value-of select="php:function('stubXSLCallback::invoke', 'date', 'formatLocaleDate', @format, @timestamp)"/>
  </xsl:template>

</xsl:stylesheet>
