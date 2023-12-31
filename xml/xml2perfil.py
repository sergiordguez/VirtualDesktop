import xml.etree.ElementTree as ET

tree = ET.parse('rutasEsquema.xml')
root = tree.getroot()

for idx, ruta in enumerate(root.findall('.//{http://www.uniovi.es}ruta')):
    svg_filename = f'ruta{idx + 1}.svg'
    with open(svg_filename, 'w') as svg_file:
        svg_file.write('<?xml version="1.0" encoding="UTF-8" ?>\n')
        svg_file.write('<svg xmlns="http://www.w3.org/2000/svg" version="2.0">\n')
        
        coordenadas = []
        for idy, hito in enumerate(ruta.findall('.//{http://www.uniovi.es}hito')):
            coords = hito.find('{http://www.uniovi.es}coordenadas').text.split(', ')
            x, y = float(coords[1])+(idy*10), float(coords[0])+(idy*10)
            coordenadas.append((x, y))
        
        polyline_points = ' '.join([f'{x},{y}' for x, y in coordenadas])
        svg_file.write(f'<polyline points="{polyline_points}" style="fill:white;stroke:red;stroke-width:4" />\n')
        
        for i, hito in enumerate(ruta.findall('.//{http://www.uniovi.es}hito')):
            nombre_hito = hito.find('{http://www.uniovi.es}nombre').text
            x, y = coordenadas[i]
            svg_file.write(f'<text x="{x}" y="{y}" style="writing-mode: tb; glyph-orientation-vertical: 0;">\n')
            svg_file.write(f'    {nombre_hito}\n')
            svg_file.write('</text>\n')
        
        svg_file.write('</svg>')

    print(f'Se ha creado el archivo SVG para la ruta {idx + 1}: {svg_filename}')
