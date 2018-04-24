---
title: Amaze UI Tags Input 使用演示
next: options.html
---

## 使用演示
---

### 调用方式

#### 使用 input

在 `input` 上添加 `data-role="tagsinput"` 属性，自动初始化为标签输入框。

`````html
<p>
  <input id="tags-1" type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo"
         data-role="tagsinput"/>
</p>
`````

```html
<input id="tags-1" type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo" data-role="tagsinput" />
```

获取 `input` 的值：

```js
$('#tags-1').val()
// "Amsterdam,Washington,Sydney,Beijing,Cairo"

$('#tags-2').tagsinput('items')
// ["Amsterdam", "Washington", "Sydney", "Beijing", "Cairo"]
```

#### 使用 select

`````html
<select id="tags-select" multiple data-role="tagsinput">
  <option value="Amsterdam">Amsterdam</option>
  <option value="Washington">Washington</option>
  <option value="Sydney">Sydney</option>
  <option value="Beijing">Beijing</option>
  <option value="Cairo">Cairo</option>
</select>
`````

```html
<select id="tags-select" multiple data-role="tagsinput">
  <option value="Amsterdam">Amsterdam</option>
  <option value="Washington">Washington</option>
  <option value="Sydney">Sydney</option>
  <option value="Beijing">Beijing</option>
  <option value="Cairo">Cairo</option>
</select>
```

获取的值时都返回数组：

```js
$('#tags-select').val();
// ["Amsterdam","Washington","Sydney","Beijing","Cairo"]

$('#tags-select').tagsinput('items');
// ["Amsterdam","Washington","Sydney","Beijing","Cairo"]
```

### 在表单中使用

`````html
<style>
  .am-form .am-tagsinput {
    min-width: 100%;
  }
</style>
<div class="am-g">
  <div class="am-u-md-8 am-u-md-centered">
    <form class="am-form" action="" method="post">
      <div class="am-form-group">
        <label for="tags-10">输入标签：</label>
        <input id="tags-10" type="text" value="" data-role="tagsinput"/>
      </div>
      <div class="am-align-right">
        <input type="submit" class="am-btn am-btn-primary" value="submit"/>
      </div>
    </form>
  </div>
</div>
`````
```html
<style>
  .am-form .am-tagsinput {
    min-width: 100%;
  }
</style>
<div class="am-g">
  <div class="am-u-md-8 am-u-md-centered">
    <form class="am-form" action="" method="post">
      <div class="am-form-group">
        <label for="tags-10">输入标签：</label>
        <input id="tags-10" type="text" value="" data-role="tagsinput"/>
      </div>
      <div class="am-align-right">
        <input type="submit" class="am-btn am-btn-primary" value="submit"/>
      </div>
    </form>
  </div>
</div>
```

### 输入提示

结合 [typeahead.js](http://twitter.github.io/typeahead.js/) 使用可以实现输入提示效果

`````html
<input id="input-sg" type="text" value="Amsterdam,Washington" data-role="tagsinput" />
<style>
  .twitter-typeahead .tt-query,
  .twitter-typeahead .tt-hint {
    margin-bottom: 0;
  }

  .twitter-typeahead .tt-hint
  {
    display: none;
  }

  .tt-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    list-style: none;
    font-size: 14px;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 0;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }
  .tt-suggestion > p {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.428571429;
    color: #333333;
    white-space: nowrap;
    margin: 0;
  }
  .tt-suggestion > p:hover,
  .tt-suggestion > p:focus,
  .tt-suggestion.tt-cursor p {
    color: #ffffff;
    text-decoration: none;
    outline: 0;
    background-color: #428bca;
  }
</style>
`````

```html
<input id="input-sg" type="text" value="Amsterdam,Washington" data-role="tagsinput" />
```

```js
var citynames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: 'js/citynames.json',
    filter: function(list) {
      return $.map(list, function(cityname) {
        return { name: cityname }; });
    }
  }
});
citynames.initialize();

$('#input-sg').tagsinput({
  typeaheadjs: {
    name: 'citynames',
    displayKey: 'name',
    valueKey: 'name',
    source: citynames.ttAdapter()
  }
});
```

### 对象作为标签

标签需要存储更多数据时，可以在标签上存储对象。

`````html
<input id="obj-as-tags" type="text" />
`````

```js
var cities = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: 'assets/cities.json'
});
cities.initialize();

var $elt = $('#obj-as-tags');
$elt.tagsinput({
  itemValue: 'value',
  itemText: 'text',
  typeaheadjs: {
    name: 'cities',
    displayKey: 'text',
    source: cities.ttAdapter()
  }
});

$elt.tagsinput('add', { "value": 1 , "text": "Amsterdam"   , "continent": "Europe"    });
$elt.tagsinput('add', { "value": 4 , "text": "Washington"  , "continent": "America"   });
$elt.tagsinput('add', { "value": 7 , "text": "Sydney"      , "continent": "Australia" });
$elt.tagsinput('add', { "value": 10, "text": "Beijing"     , "continent": "Asia"      });
$elt.tagsinput('add', { "value": 13, "text": "Cairo"       , "continent": "Africa"    });
```

获取值：

```js
$('#obj-as-tags').val();
// "1,4,7,10,13"

$('#obj-as-tags').tagsinput('items');
// [{"value":1,"text":"Amsterdam","continent":"Europe"},{"value":4,"text":"Washington","continent":"America"},{"value":7,"text":"Sydney","continent":"Australia"},{"value":10,"text":"Beijing","continent":"Asia"},{"value":13,"text":"Cairo","continent":"Africa"}]
```

### 设置标签 class

`````html
<input id="tag-color" type="text" />
`````

```js
// tag color
var cities = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: 'js/cities.json'
});
cities.initialize();

var $tc = $('#tag-color');
$tc.tagsinput({
  tagClass: function(item) {
    switch (item.continent) {
      case 'Europe'   : return 'am-badge am-badge-primary';
      case 'America'  : return 'am-badge am-badge-danger';
      case 'Australia': return 'am-badge am-badge-success';
      case 'Africa'   : return 'am-badge';
      case 'Asia'     : return 'am-badge am-badge-warning';
    }
  },
  itemValue: 'value',
  itemText: 'text',
  typeaheadjs: {
    name: 'cities',
    displayKey: 'text',
    source: cities.ttAdapter()
  }
});
$tc.tagsinput('add', { "value": 1 , "text": "Amsterdam"   , "continent": "Europe"    });
$tc.tagsinput('add', { "value": 4 , "text": "Washington"  , "continent": "America"   });
$tc.tagsinput('add', { "value": 7 , "text": "Sydney"      , "continent": "Australia" });
$tc.tagsinput('add', { "value": 10, "text": "Beijing"     , "continent": "Asia"      });
$tc.tagsinput('add', { "value": 13, "text": "Cairo"       , "continent": "Africa"    });
```

<script src="./js/typeahead.min.js"></script>
<script src="../amazeui.tagsinput.js"></script>
<script>
// 输入提示
var citynames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: 'js/citynames.json',
    filter: function(list) {
      return $.map(list, function(cityname) {
        return { name: cityname }; });
    }
  }
});
citynames.initialize();

$('#input-sg').tagsinput({
  typeaheadjs: {
    name: 'citynames',
    displayKey: 'name',
    valueKey: 'name',
    source: citynames.ttAdapter()
  }
});

  // object as tags
  var cities = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: 'js/cities.json'
  });
  cities.initialize();

  var $elt = $('#obj-as-tags');
  $elt.tagsinput({
    itemValue: 'value',
    itemText: 'text',
    typeaheadjs: {
      name: 'cities',
      displayKey: 'text',
      source: cities.ttAdapter()
    }
  });
  $elt.tagsinput('add', { "value": 1 , "text": "Amsterdam"   , "continent": "Europe"    });
  $elt.tagsinput('add', { "value": 4 , "text": "Washington"  , "continent": "America"   });
  $elt.tagsinput('add', { "value": 7 , "text": "Sydney"      , "continent": "Australia" });
  $elt.tagsinput('add', { "value": 10, "text": "Beijing"     , "continent": "Asia"      });
  $elt.tagsinput('add', { "value": 13, "text": "Cairo"       , "continent": "Africa"    });

// colors
/*var cities = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: 'js/cities.json'
});
cities.initialize();*/

var $tc = $('#tag-color');
$tc.tagsinput({
  tagClass: function(item) {
    switch (item.continent) {
      case 'Europe'   : return 'am-badge am-badge-primary';
      case 'America'  : return 'am-badge am-badge-danger';
      case 'Australia': return 'am-badge am-badge-success';
      case 'Africa'   : return 'am-badge';
      case 'Asia'     : return 'am-badge am-badge-warning';
    }
  },
  itemValue: 'value',
  itemText: 'text',
  typeaheadjs: {
    name: 'cities',
    displayKey: 'text',
    source: cities.ttAdapter()
  }
});
$tc.tagsinput('add', { "value": 1 , "text": "Amsterdam"   , "continent": "Europe"    });
$tc.tagsinput('add', { "value": 4 , "text": "Washington"  , "continent": "America"   });
$tc.tagsinput('add', { "value": 7 , "text": "Sydney"      , "continent": "Australia" });
$tc.tagsinput('add', { "value": 10, "text": "Beijing"     , "continent": "Asia"      });
$tc.tagsinput('add', { "value": 13, "text": "Cairo"       , "continent": "Africa"    });
</script>
