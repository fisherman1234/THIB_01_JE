<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>

    <title>JavaScript tabs example</title>
<script type="text/javascript" src="editor/editor.js"></script>
<script type="text/javascript" src="editor/tabs.js"></script>

    <style type="text/css">
      body { font-size: 80%; font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif; }
      ul#tabs { list-style-type: none; margin: 30px 0 0 0; padding: 0 0 0.3em 0; }
      ul#tabs li { display: inline; }
      ul#tabs li a { color: #42454a; background-color: #dedbde; border: 1px solid #c9c3ba; border-bottom: none; padding: 0.3em; text-decoration: none; }
      ul#tabs li a:hover { background-color: #f1f0ee; }
      ul#tabs li a.selected { color: #000; background-color: #f1f0ee; font-weight: bold; padding: 0.7em 0.3em 0.38em 0.3em; }
      div.tabContent { border: 1px solid #c9c3ba; padding: 0.5em; background-color: #f1f0ee; }
      div.tabContent.hide { display: none; }
    </style>



  </head>
  <body onload="init()">
    <h1>JavaScript tabs example</h1>

    <ul id="tabs">
      <li><a href="#about">About JavaScript tabs</a></li>
      <li><a href="#advantages">Advantages of tabs</a></li>
      <li><a href="#usage">Using tabs</a></li>
    </ul>

    <div class="tabContent" id="about">
      <h2>About JavaScript tabs</h2>
      <div>
      <textarea  id="about_area">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam laoreet pulvinar nisl. Nullam elit. Cras sollicitudin molestie mauris. Nulla dapibus velit sed mauris. In tellus. Praesent eget ipsum et velit tempus suscipit. Fusce mattis, erat a mollis placerat, enim orci rutrum lorem, ac fermentum diam ligula non nulla. Integer luctus lacus at nibh. Aliquam a sem quis neque convallis vehicula. Etiam nec tellus quis risus feugiat eleifend. Praesent porttitor ante eget ipsum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Sed ullamcorper orci ac pede. Nullam metus eros, pretium vel, rhoncus id, blandit sed, sapien. Aliquam dignissim mi vitae felis. Nullam ultrices tellus quis eros. Phasellus dolor felis, feugiat et, porta et, semper euismod, purus. Proin semper nisi quis erat faucibus tincidunt.</textarea>
      </div>
    </div>

    <div class="tabContent" id="advantages">
      <h2>Advantages of tabs</h2>
      <div>
       <textarea  id="advantages_area">Duis justo lectus, porta sit amet, sollicitudin et, convallis vel, dolor. Vestibulum massa. Sed lacinia tristique elit. Phasellus ac sapien non est gravida dapibus. Nulla ac dolor ac turpis mollis laoreet. Phasellus tincidunt. Phasellus adipiscing nisi et nisi. Mauris at nulla. Sed tempor, magna vel rhoncus lacinia, tellus mi venenatis nisi, eu ultrices neque neque quis tortor. Vivamus quis elit. Ut molestie dolor vitae mauris. Maecenas facilisis tempor risus. Ut vel orci eu libero interdum bibendum.</textarea>
      </div>
    </div>

    <div class="tabContent" id="usage">
      <h2>Using tabs</h2>
      <div>
       <textarea  id="usage_area">Aliquam porta. Cras dui est, commodo vitae, egestas quis, consectetuer sed, pede. Aliquam convallis, lacus in bibendum vulputate, mauris lectus mattis lectus, a semper neque arcu sit amet urna. Aenean imperdiet. Morbi suscipit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Nam nulla felis, imperdiet eget, tristique et, pulvinar et, leo. Maecenas rutrum, est at condimentum bibendum, odio purus bibendum nulla, luctus imperdiet velit sem quis ligula. Nulla id nulla eu sem commodo volutpat. Pellentesque neque risus, elementum nec, placerat nec, aliquet eget, lacus. Quisque cursus. Vestibulum fermentum tellus sed odio. Sed metus. Phasellus dui. Donec orci. Nulla convallis. Praesent non eros. Sed magna elit, rutrum et, tincidunt sit amet, suscipit at, odio. Sed at sem eu lacus semper aliquam. Proin lorem.</textarea>
      </div>
    </div>

<script>



</script>
  </body>
</html>
