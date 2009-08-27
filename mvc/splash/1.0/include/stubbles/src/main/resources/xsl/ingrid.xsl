<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:ixsl="http://www.w3.org/1999/XSL/TransformOutputAlias"
    xmlns:php="http://php.net/xsl"
    xmlns:stub="http://stubbles.net/stub"
    exclude-result-prefixes="php ixsl stub">
  <xsl:template match="stub:ingrid">
    <ul>
      <xsl:copy-of select="@*[name() !='prefix']"/>
      <xsl:apply-templates/>
    </ul>
  </xsl:template>

  <xsl:template match="stub:ingrid//row">
    <li class="clearfix">
      <xsl:copy-of select="@*"/>
      <xsl:apply-templates/>
    </li>
  </xsl:template>

  <xsl:template match="stub:ingrid//markup">
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="stub:ingrid//left">
    <xsl:call-template name="stub_ingrid_row_element">
      <xsl:with-param name="type">
        <xsl:text>left</xsl:text>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="stub:ingrid//right">
    <xsl:call-template name="stub_ingrid_row_element">
      <xsl:with-param name="type">
        <xsl:text>right</xsl:text>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template match="stub:ingrid//both">
    <xsl:call-template name="stub_ingrid_row_element">
      <xsl:with-param name="type">
        <xsl:text>both</xsl:text>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:template>

  <xsl:template name="stub_ingrid_row_element">
    <xsl:param name="type" select="@type"/>
    <div>
      <xsl:copy-of select="@*[name() !='class']"/>
        <xsl:attribute name="class">
        <xsl:value-of select="$type"/>
        <xsl:if test="@class">
          <xsl:text> </xsl:text>
          <xsl:value-of select="@class"/>
        </xsl:if>
      </xsl:attribute>
      <xsl:apply-templates/>
    </div>
  </xsl:template>

  <xsl:template match="stub:ingrid//label">
    <xsl:variable name="prefix">
      <xsl:value-of select="ancestor::stub:ingrid/@prefix"/>
    </xsl:variable>
    <label>
      <xsl:copy-of select="@*[name() !='for' and name() !='class' and name() !='colon' and name() !='mandatory']"/>
      <xsl:attribute name="for">
        <xsl:choose>
          <xsl:when test="starts-with(@for, concat($prefix, '_'))">
            <xsl:value-of select="@for"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="concat($prefix, '_', @for)"/>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:attribute> 
      <xsl:attribute name="class">      
        <ixsl:choose>
          <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{@for}'])/errors) &gt; 0">
            <ixsl:value-of select="@class"/>
            <xsl:text>text error</xsl:text>
          </ixsl:when>
          <ixsl:otherwise>
            <ixsl:value-of select="@class"/>
            <xsl:text>text</xsl:text>
          </ixsl:otherwise>
        </ixsl:choose>
      </xsl:attribute>
      <xsl:variable name="labelpart">
        <xsl:text>label_</xsl:text>
        <xsl:value-of select="$prefix"/>
        <xsl:text>_</xsl:text>
        <xsl:value-of select="@for"/>
      </xsl:variable>
      <xsl:call-template name="stub:include">
        <xsl:with-param name="part" select="$labelpart"/>
      </xsl:call-template>
      <xsl:if test="not(@colon = 'false' or @mandatory = 'true')">
        <xsl:text>:</xsl:text>
      </xsl:if>
      <xsl:if test="@mandatory = 'true'">
        <xsl:text>:*</xsl:text>
      </xsl:if>
    </label>
  </xsl:template>

  <xsl:template match="stub:ingrid//info">
    <xsl:variable name="prefix">
      <xsl:value-of select="ancestor::stub:ingrid/@prefix"/>
    </xsl:variable>
    <div class="info">
      <xsl:copy-of select="@*[name() !='class' and name() !='for']"/>
      <div class="{@class} infoBoxMagix">
        <xsl:attribute name="id">
          <xsl:value-of select="concat('info.', $prefix, '_', @for)"/>
        </xsl:attribute>
        <div class="header"/>
        <div class="content">
          <xsl:apply-templates/>
        </div>
        <div class="footer"/>
      </div>
    </div>
  </xsl:template>

  <xsl:template match="stub:ingrid//item">
    <xsl:variable name="prefix">
      <xsl:value-of select="ancestor::stub:ingrid/@prefix"/>
    </xsl:variable>
    <xsl:call-template name="stub_ingrid_field_object">
      <xsl:with-param name="prefix" select="$prefix"/>
    </xsl:call-template>
    <xsl:if test="not(ancestor::item[@type='multi'])">
      <xsl:call-template name="stub_ingrid_field_error">
        <xsl:with-param name="prefix" select="$prefix"/>
        <xsl:with-param name="name" select="@name"/>
        <xsl:with-param name="type" select="@type"/>
      </xsl:call-template>
    </xsl:if>
  </xsl:template>

  <xsl:template name="stub_ingrid_field_error">
    <xsl:param name="prefix" select="@prefix"/>
    <xsl:param name="name" select="@name"/>
    <xsl:param name="type" select="@type"/>
    <xsl:param name="nx" select="@nx"/>
    <ixsl:if>
      <xsl:attribute name="test">
        <xsl:choose>
          <xsl:when test="$type = 'multi'">
            <xsl:for-each select=".|.//item">
              <xsl:text>count(/document/request/value[@name='</xsl:text>
              <xsl:value-of select="$prefix"/>
              <xsl:text>_</xsl:text>
              <xsl:value-of select="@name"/>
              <xsl:text>']/errors) &gt; 0</xsl:text>
              <xsl:if test="position() != last()">
                <xsl:text> or </xsl:text>
              </xsl:if>
            </xsl:for-each>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>count(/document/request/value[@name='</xsl:text>
            <xsl:value-of select="$prefix"/>
            <xsl:text>_</xsl:text>
            <xsl:value-of select="$name"/>
            <xsl:text>']/errors) &gt; 0</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <div>
        <xsl:copy-of select="@style"/>
        <xsl:attribute name="class">error</xsl:attribute>
        <xsl:choose>
          <xsl:when test="$type = 'multi'">
            <ul>
              <xsl:for-each select=".|.//item[not(@type = 'freetext')]">
                <xsl:if test="not(@name=preceding::item[ancestor::stub:ingrid[@prefix=$prefix]]/@name)">
                  <ixsl:if test="count(/document/request/value[@name='{$prefix}.{@name}']/errors) &gt; 0">
                  <!-- TODO: for each error -->
                    <li>
                      <xsl:choose>
                        <xsl:when test="not(../@uselabel = 'false') and not(../@uselabel = 'one') and not(@name = '')">
                          <span class="label">
                            <xsl:call-template name="stub:include">
                              <xsl:with-param name="part" select="concat('label_', $prefix, '_', @name)"/>
                            </xsl:call-template>
                          </span>
                        </xsl:when>
                        <xsl:otherwise>
                          <stub:include part="fielderrormsg_field" href="{$product}/txt/error.xml"/>
                          <xsl:value-of select="concat(' ', position())"/>
                        </xsl:otherwise>
                      </xsl:choose>
                      <xsl:text>: </xsl:text>
                      <ixsl:apply-templates select="/document/request/value[@name='{$prefix}_{@name}']/errors/node()"/>
                    </li>
                  </ixsl:if>
                </xsl:if>
              </xsl:for-each>
            </ul>
          </xsl:when>
          <xsl:otherwise>
            <!-- TODO: for each error -->
            <ixsl:apply-templates select="/document/request/value[@name='{$prefix}_{$name}']/node()"/>
          </xsl:otherwise>
        </xsl:choose>
      </div>
    </ixsl:if>
  </xsl:template>
  
  <xsl:template name="stub_ingrid_field_object">
    <xsl:param name="type" select="@type"/>
    <xsl:param name="prefix" select="@prefix"/>
    <xsl:param name="name" select="@name"/>
    <xsl:param name="fullname" select="@fullname"/>
    <xsl:param name="value" select="@value"/>
    <xsl:param name="class" select="@class"/>
    <xsl:param name="style" select="@style"/>
    <xsl:param name="size" select="@size"/>
    <xsl:param name="path" select="@path"/>
    <xsl:param name="readonly" select="@readonly"/>
    <xsl:param name="disabled" select="@disabled"/>
    <xsl:param name="rows" select="@rows"/>
    <xsl:param name="cols" select="@cols"/>
    <xsl:param name="setdefault" select="@setdefault"/>
    <xsl:param name="default" select="@default"/>
    <xsl:param name="maxlength" select="@maxlength"/>
    <xsl:param name="optionlabel" select="@optionlabel"/>
    <xsl:param name="focus" select="@focus"/>
    <xsl:param name="tabindex" select="@tabindex"/>
    <xsl:param name="adddefaultoption" select="@adddefaultoption"/>
    <xsl:param name="omitoptioninclude" select="@omitoptioninclude"/>
    <xsl:param name="checked" select="@checked"/>
    <xsl:variable name="myid">
      <xsl:choose>
        <xsl:when test="@id">
          <xsl:value-of select="@id"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="concat($prefix, '_', $name)"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="myname">
      <xsl:choose>
        <xsl:when test="@fullname">
          <xsl:value-of select="@fullname"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="concat($prefix, '_', $name)"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:choose>
      <xsl:when test="$type = 'multi'">
        <div>
          <xsl:copy-of select="@style"/>
          <ixsl:attribute name="class"><xsl:value-of select="$class"/> multi clearfix</ixsl:attribute>
          <xsl:if test="$name != ''">
            <ixsl:attribute name="id"><xsl:value-of select="concat($prefix, '_', $name)"/></ixsl:attribute>
          </xsl:if>
          <xsl:apply-templates/>
        </div>
      </xsl:when>
      <xsl:when test="$type = 'freetext'">
        <div>
          <xsl:if test="not(@noid and @noid = 'true')">
            <ixsl:attribute name="id">
              <xsl:value-of select="$myid"/>
            </ixsl:attribute>
          </xsl:if>
          <xsl:copy-of select="@style"/>
          <ixsl:choose>
            <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> text error</ixsl:attribute>
            </ixsl:when>
            <ixsl:otherwise>
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> text</ixsl:attribute>
            </ixsl:otherwise>
          </ixsl:choose>
          <xsl:apply-templates/>
        </div>
      </xsl:when>
      <xsl:when test="$type = 'image'">
        <span class="btn"><input type="{$type}" class="btn_submit" value="{$value}" name="{$prefix}_{$name}" /></span>
      </xsl:when>  
      <xsl:when test="$type = 'submit'">
        <input class="btn_submit" type="{$type}" value="{$value}" name="{$prefix}_{$name}" />
      </xsl:when>
      <xsl:when test="$type = 'hidden'">
        <input type="{$type}" value="{$value}" name="{$prefix}_{$name}"/>
      </xsl:when>
      <xsl:when test="$type = 'include'">
        <xsl:call-template name="stub:include">
          <xsl:with-param name="part" select="concat($prefix, '_', $name)"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:when test="$type = 'file'">
        <input type="file" name="{$prefix}_{$name}" id="{$myid}">
          <xsl:copy-of select="@style"/>
          <xsl:if test="$tabindex != ''">
            <xsl:attribute name="tabindex">
              <xsl:value-of select="$tabindex"/>
            </xsl:attribute>
          </xsl:if>
          <ixsl:if test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
            <ixsl:attribute name="class">error</ixsl:attribute>
          </ixsl:if>
        </input>
      </xsl:when>
      <xsl:when test="$type = 'dynamic'">
        <input type="select" name="{$prefix}_{$name}" id="{$myid}">
          <xsl:copy-of select="@style"/>
          <xsl:copy-of select="@onclick"/>
          <xsl:copy-of select="@onchange"/>
          <xsl:if test="$readonly = 'true'">
            <xsl:attribute name="readonly">true</xsl:attribute>
          </xsl:if>
          <xsl:if test="$tabindex != ''">
            <xsl:attribute name="tabindex">
              <xsl:value-of select="$tabindex"/>
            </xsl:attribute>
          </xsl:if>
          <xsl:if test="$default != ''">
            <xsl:attribute name="default">
              <xsl:value-of select="$default"/>
            </xsl:attribute>
          </xsl:if>
          <ixsl:choose>
            <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> select error</ixsl:attribute>
            </ixsl:when>
            <ixsl:otherwise>
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> select</ixsl:attribute>
            </ixsl:otherwise>
          </ixsl:choose>
          <xsl:if test="$adddefaultoption = 'true'">
            <option value="">
              <xsl:call-template name="stub:include">
                <xsl:with-param name="part" select="concat('default_', $prefix, '_', $name)"/>
              </xsl:call-template>
            </option>
          </xsl:if>
          <ixsl:for-each select="{$path}">
            <ixsl:element name="{{name()}}">
              <ixsl:if test="/document/forms/{$prefix}/{$name} = @value">
                <ixsl:attribute name="selected">selected</ixsl:attribute>
              </ixsl:if>
              <ixsl:copy-of select="@*"/>
              <ixsl:apply-templates/>
            </ixsl:element>
          </ixsl:for-each> 
        </input>
        <xsl:apply-templates/>
      </xsl:when>
      <xsl:when test="$type = 'radio' or $type = 'check'">
        <xsl:choose>
          <xsl:when test="not(.//option) and $value != ''">
            <input type="{$type}" value="{$value}" name="{$prefix}_{$name}">
              <xsl:copy-of select="@style"/>
              <xsl:copy-of select="@onclick"/>
              <xsl:copy-of select="@onchange"/>
              <xsl:attribute name="id">
                <xsl:choose>
                  <xsl:when test="$type = 'radio' or ($type = 'check' and $value != '')">
                    <xsl:value-of select="concat($prefix, '_', $name, '-', $value)"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:value-of select="concat($prefix, '_', $name)"/>
                  </xsl:otherwise>
                </xsl:choose>
              </xsl:attribute>
              <xsl:if test="$default = 'true'">
                <xsl:attribute name="checked">checked</xsl:attribute>
              </xsl:if>
              <ixsl:choose>
                <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
                  <ixsl:attribute name="class"><xsl:value-of select="$class"/><xsl:text> </xsl:text><xsl:value-of select="$type"/> error</ixsl:attribute>
                </ixsl:when>
                <ixsl:otherwise>
                  <ixsl:attribute name="class"><xsl:value-of select="$class"/><xsl:text> </xsl:text><xsl:value-of select="$type"/></ixsl:attribute>
                </ixsl:otherwise>
              </ixsl:choose>
            </input>
          </xsl:when>
          <xsl:otherwise>
            <div>
              <xsl:if test="@display">
                <xsl:attribute name="style">display:<xsl:value-of select="@display"/>;</xsl:attribute>
              </xsl:if>
              <xsl:apply-templates select="option|ixsl:if|xsl:if|ixsl:choose|xsl:choose|ixsl:when|xsl:when|ixsl:otherwise|xsl:otherwise"/>
            </div>
          </xsl:otherwise>
        </xsl:choose>
        <xsl:apply-templates select="./text()|./text/node()|./text/text()"/>
      </xsl:when>
      <xsl:when test="$type = 'select'">
        <select name="{$prefix}.{$name}" id="{$myid}">
          <xsl:copy-of select="@*[name()!='class' and name()!='omitoptioninclude' and name()!='name' and name()!='id' and name()!='type']"/>
          <xsl:if test="$disabled = 'true'">
            <xsl:attribute name="disabled">true</xsl:attribute>
          </xsl:if>
          <xsl:if test="$tabindex != ''">
            <xsl:attribute name="tabindex">
              <xsl:value-of select="$tabindex"/>
            </xsl:attribute>
          </xsl:if>
          <ixsl:choose>
            <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> select error</ixsl:attribute>
            </ixsl:when>
            <ixsl:otherwise>
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> select</ixsl:attribute>
            </ixsl:otherwise>
          </ixsl:choose>
          <xsl:for-each select="option">
            <option value="{@value}">
              <xsl:if test="@default = 'true'">
                <xsl:copy-of select="./@default"/>
              </xsl:if>
              <xsl:choose>
                <xsl:when test="$omitoptioninclude = 'true'">
                  <xsl:apply-templates/>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:call-template name="stub:include">
                    <xsl:with-param name="part" select="concat('option_', $prefix, '_', $name, '-', @value)"/>
                  </xsl:call-template>
                </xsl:otherwise>
              </xsl:choose>
            </option>
          </xsl:for-each>
          <xsl:for-each select="optioninclude">
            <xsl:call-template name="stub:include">
              <xsl:with-param name="href" select="@href"/>
              <xsl:with-param name="part" select="@part"/>
            </xsl:call-template>
          </xsl:for-each>
          <xsl:apply-templates select="*[name()!='option' and name()!='optioninclude']"/>
        </select>
      </xsl:when>
      <xsl:when test="$type = 'area'">
        <textarea name="{$prefix}.{$name}" id="{$myid}">
          <xsl:copy-of select="@style"/>
          <xsl:copy-of select="@onclick"/>
          <xsl:copy-of select="@onchange"/>
          <xsl:if test="$cols != ''">
            <xsl:attribute name="cols"><xsl:value-of select="$cols"/></xsl:attribute>
          </xsl:if>
          <xsl:if test="$rows != ''">
            <xsl:attribute name="rows"><xsl:value-of select="$rows"/></xsl:attribute>
          </xsl:if>
          <xsl:if test="$tabindex != ''">
            <xsl:attribute name="tabindex"><xsl:value-of select="$tabindex"/></xsl:attribute>
          </xsl:if>
          <ixsl:choose>
            <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> area error</ixsl:attribute>
            </ixsl:when>
            <ixsl:otherwise>
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> area</ixsl:attribute>
            </ixsl:otherwise>
          </ixsl:choose>
          <xsl:for-each select="default">
            <xsl:apply-templates/>
          </xsl:for-each>
        </textarea>
        <xsl:apply-templates select="*[name()!='default']"/>
      </xsl:when>
      <xsl:otherwise>
        <input type="text" name="{$myid}" id="{$myid}">
          <xsl:copy-of select="@style"/>
          <xsl:copy-of select="@onclick"/>
          <xsl:copy-of select="@onchange"/>
          <xsl:copy-of select="@onkeypress"/>
          <xsl:copy-of select="@onkeydown"/>
          <xsl:copy-of select="@onkeyup"/>
          <xsl:if test="$type = 'password'">
            <xsl:attribute name="type"><xsl:value-of select="$type"/></xsl:attribute>
          </xsl:if>
          <xsl:if test="$size != ''">
            <xsl:attribute name="size"><xsl:value-of select="$size"/></xsl:attribute>
          </xsl:if>
          <xsl:if test="$maxlength != ''">
            <xsl:attribute name="maxlength"><xsl:value-of select="$maxlength"/></xsl:attribute>
          </xsl:if>
          <xsl:if test="$readonly = 'true'">
            <xsl:attribute name="readonly">true</xsl:attribute>
          </xsl:if>
          <xsl:if test="$tabindex != ''">
            <xsl:attribute name="tabindex">
              <xsl:value-of select="$tabindex"/>
            </xsl:attribute>
          </xsl:if>
          <xsl:if test="$default != ''">
            <xsl:attribute name="default">
              <xsl:value-of select="$default"/>
            </xsl:attribute>
          </xsl:if>
          <ixsl:choose>
            <ixsl:when test="count(/document/request/value[@name = '{$prefix}_{$name}']/errors) &gt; 0">
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> text error</ixsl:attribute>
            </ixsl:when>
            <ixsl:otherwise>
              <ixsl:attribute name="class"><xsl:value-of select="$class"/> text</ixsl:attribute>
            </ixsl:otherwise>
          </ixsl:choose>
        </input>
        <xsl:apply-templates/>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="$focus = 'true'">
      <script type="text/javascript">document.getElementById("<xsl:value-of select="concat($prefix, '_', $name)"/>").focus();</script>
    </xsl:if>
  </xsl:template>
  
<!-- 
*
*
*
*
*
*
*
*
older Version replaced by INGRID
*
*
 -->
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

</xsl:stylesheet>
