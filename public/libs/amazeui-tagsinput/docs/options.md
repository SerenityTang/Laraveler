---
title: Switch 参数、方法、事件
prev: demo.html
---

## 选项
---

### tagClass

设置标签类名，字符串或者返回字符串的函数。

```js
$('input').tagsinput({
  tagClass: 'big'
});

$('input').tagsinput({
  tagClass: function(item) {
    return (item.length > 10 ? 'big' : 'small');
  }
});
```

### itemValue

使用对象作为标签的值时，`itemValue` 要设置为对象中包含的属性的属性名。

```js
$('input').tagsinput({
    itemValue: 'id'
});

$('input').tagsinput({
  itemValue: function(item) {
    return item.id;
  }
});
```

### itemText

```js
$('input').tagsinput({
  itemText: 'label'
});

$('input').tagsinput({
  itemText: function(item) {
    return item.label;
  }
});
```

### confirmKeys

确定添加标签的按钮，默认为 `[13, 188]`，即「回车键」和「空格键」。

```js
$('input').tagsinput({
  confirmKeys: [13, 44]
});
```

### maxTags

最大标签数量 (default: `undefined`)。 When maxTags is reached, a class 'bootstrap-tagsinput-max' is placed on the tagsinput element.

```js
$('input').tagsinput({
  maxTags: 3
});
```

### maxChars

标签字符长度限制 (default: `undefined`)。

```js
$('input').tagsinput({
  maxChars: 8
});
```

### trimValue

是否移除标签左右的空格 (default: `false`)。

```js
$('input').tagsinput({
  trimValue: true
});
```

### allowDuplicates

是否允许重复的标签 (default: `false`)。

```js
$('input').tagsinput({
  allowDuplicates: true
});
```

### freeInput

Allow creating tags which are not returned by typeahead's source (default: true)

This is only possible when using string as tags. When itemValue option is set, this option will be ignored.

```js
$('input').tagsinput({
  typeahead: {
    source: ['Amsterdam', 'Washington', 'Sydney', 'Beijing', 'Cairo']
  },
  freeInput: true
});
```

### typeahead

Object containing typeahead specific options

**source**

An array (or function returning a promise or array), which will be used as source for a typeahead.

```js
$('input').tagsinput({
  typeahead: {
    source: ['Amsterdam', 'Washington', 'Sydney', 'Beijing', 'Cairo']
  }
});
```

```js
$('input').tagsinput({
  typeahead: {
    source: function(query) {
      return $.get('http://someservice.com');
    }
  }
});
```

### onTagExists

Function invoked when trying to add an item which allready exists. By default, the existing tag hides and fades in.

```js
$('input').tagsinput({
  onTagExists: function(item, $tag) {
    $tag.hide.fadeIn();
  }
});
```

## 方法
---

### add

添加一个标签。

```js
$('input').tagsinput('add', 'some tag');

$('input').tagsinput('add', { id: 1, text: 'some tag' });
```
### remove

移除一个标签。

```js
$('input').tagsinput('remove', 'some tag');

$('input').tagsinput('remove', { id: 1, text: 'some tag' });
```
### removeAll

移除所有标签。

```js
$('input').tagsinput('removeAll');
```

### focus

是标签输入框获得焦点。

```js
$('input').tagsinput('focus');
```

### input

返回标签输入框里面的 `<input />`，用于添加自定义的输入提示等。

```js
var $elt = $('input').tagsinput('input');
```

### refresh

刷新标签输入框 UI。

This might be usefull when you're adding objects as tags. When an object's text changes, you'll have to refresh to update the matching tag's text.

```js
$('input').tagsinput('refresh');
```

### destroy

销毁标签输入实例。

```js
$('input').tagsinput('destroy');
```

## 事件
---

### beforeItemAdd

一个标签即将添加前触发。

```js
$('input').on('beforeItemAdd', function(event) {
  // event.item: contains the item
  // event.cancel: set to true to prevent the item getting added
});
```

### itemAdded

一个标签添加完成后触发。

```js
$('input').on('itemAdded', function(event) {
  // event.item: contains the item
});
```

### beforeItemRemove

一个标签即将移除前触发。

```js
$('input').on('beforeItemRemove', function(event) {
  // event.item: contains the item
  // event.cancel: set to true to prevent the item getting removed
});
```

### itemRemoved

一个标签移除完成后触发。

```js
$('input').on('itemRemoved', function(event) {
  // event.item: contains the item
});
```
