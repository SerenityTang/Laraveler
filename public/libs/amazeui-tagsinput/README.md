# Amaze UI Tags Input
---

Amaze UI 标签输入插件。

项目源自 [Bootstrap Tags Input](https://github.com/timschlechter/bootstrap-tagsinput)，样式调整为 Amaze UI 风格。

- [使用示例](http://amazeui.github.io/tagsinput/docs/demo.html)
- [参数说明](http://amazeui.github.io/tagsinput/docs/options.html)

**使用说明：**

1. 获取 Amaze UI Tags Input

  - [直接下载](https://github.com/amazeui/tagsinput/archive/master.zip)
  - 使用 NPM: `npm install amazeui-tagsinput`

2. 在 Amaze UI 样式之后引入 Tags Input 样式：

  Amaze UI Tagsinput 依赖 Amaze UI 样式。

  ```html
  <link rel="stylesheet" href="path/to/amazeui.min.css"/>
  <link rel="stylesheet" href="path/to/amazeui.tagsinput.css"/>
  ```

3. 在 jQuery 之后引入 Tags Input 插件：

  ```html
  <script src="path/to/jquery.min.js"></script>
  <script src="path/to/amazeui.tagsinput.min.js"></script>
  ```

4. 初始化 Tags Input:

  ```js
  $(function() {
    $('#my-tags').tagsinput();
  });
  ```

  可以监听到 jQuery Ready 事件的 DOM 也可以使用 `data-am-tagsinput` 钩子自动初始化：

  ```html
  <input type="text" data-am-tagsinput />
  ```
