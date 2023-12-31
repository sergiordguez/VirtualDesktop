import xml.etree.ElementTree as ET

tree = ET.parse("rutasEsquema.xml")
root = tree.getroot()

for idx, ruta in enumerate(root.findall(".//{http://www.uniovi.es}ruta")):
    nombre = ruta.find("{http://www.uniovi.es}nombre").text
    coordenadas = []

    for hito in ruta.findall(".//{http://www.uniovi.es}hito"):
        nombre_hito = hito.find("{http://www.uniovi.es}nombre").text
        coordenadas_hito = hito.find("{http://www.uniovi.es}coordenadas").text.split(", ")
        latitud, longitud = coordenadas_hito
        coordenadas.append((float(longitud), float(latitud)))

    kml_content = f"""<?xml version="1.0" encoding="UTF-8"?>
    <kml xmlns="http://www.opengis.net/kml/2.2">
        <Document>
            <name>{nombre.replace(" ", "_")}.kml</name>
            <description>Ruta: {nombre}</description>
            <Placemark>
            <name>{nombre}</name>
            <LineString>
                <extrude>1</extrude>
                <tessellate>1</tessellate>
                <coordinates>
                {' '.join([f'{lon},{lat},0.0' for lon, lat in coordenadas])}
                </coordinates>
                <altitudeMode>relativeToGround</altitudeMode>
            </LineString>
            <Style> id='lineaRoja'>
                <LineStyle>
                <color>#ff0000ff</color>
                <width>5</width>
                </LineStyle>
            </Style>
            </Placemark>
        </Document>
    </kml>
    """

    with open(f"ruta{idx + 1}.kml", "w", encoding="utf-8") as kml_file:
        kml_file.write(kml_content)

print("Archivos KML creados exitosamente.")
