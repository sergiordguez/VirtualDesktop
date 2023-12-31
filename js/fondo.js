class Fondo{
  constructor(pais, capital, coordenadas_capital){
      this.pais = pais;
      this.capital = capital;
      this.coordenadas_capital = coordenadas_capital;
  }

  recibirFoto(){
    $(document).ready(function() {
        $.ajax({
          url: 'https://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?',
          dataType: 'jsonp',
          data: {
            format: 'json',
            tags: 'tirana', 
            tagmode: 'any'
          },
          success: function(data) {
            var imageUrl = data.items[0].media.m.replace("_m", "_b");
            $('body').css('background-image', 'url(' + imageUrl + ')')
            .css("background-size", "cover");
          }
        });
      });
  }
}