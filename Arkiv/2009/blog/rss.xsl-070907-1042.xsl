<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:template match="/">
<html>
<head>
<title><xsl:value-of select="rss/channel/title"/></title>
<link rel="stylesheet" type="text/css" href="http://iloblog.kemo-kasper.dk/css/rss.css"/>
</head>
<body>
<div id="containerDiv">
<div id="headerDiv">
<h1><a href="http://iloblog.kemo-kasper.dk/blog?Home"><xsl:value-of select="rss/channel/title"/></a></h1>
<p><xsl:value-of select="rss/channel/description"/></p>
</div>
<div id="mainDiv">
<xsl:for-each select="rss/channel/item">
<div class="post">
<h2><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title"/></a></h2>
<span class="category"><xsl:value-of select="category"/></span>
<xsl:value-of select="description"/>
<br/>
</div>
</xsl:for-each>
</div>
</div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>