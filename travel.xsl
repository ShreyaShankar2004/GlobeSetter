<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
    <body>
      <h2>Travel Data</h2>
      <table border="1">
        <tr bgcolor="#9acd32">
           <th>Customer Name</th>
           <th>Destination</th>
           <th>Rooms</th>
           <th>Guests</th>
           <th>Check-In Date</th>
           <th>Check-Out Date</th>
        </tr>
        <xsl:for-each select="TravelCatalog/Customer">
          <xsl:sort select="Check-InDate"/>
          <tr>
            <td><xsl:value-of select="Name"/></td>
            <td><xsl:value-of select="Destination"/></td>
            <td><xsl:value-of select="Rooms"/></td>
            <td><xsl:value-of select="Guests"/></td>
            <td><xsl:value-of select="Check-InDate"/></td>
            <td><xsl:value-of select="Check-OutDate"/></td>
          </tr>
        </xsl:for-each>
      </table>
    </body>
  </html>
</xsl:template>

</xsl:stylesheet>
