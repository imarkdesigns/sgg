<!-- Region Popover -->
<!-- Region fields are available in this template -->
<h5>{{#if title}} {{title}} {{else}} {{id}} {{/if}}</h5>
<p>Status: {{status_text}}</p>

<!-- Show all linked Database Objects: -->
{{#each objects}}

  <!-- DB Object are available inside of this block -->

  <h5>{{title}}</h5>
  <!-- When you need to render a field as HTML, use 3 curly braces instead of 2:-->
  <p>{{{description}}}</p>
  <p><em>{{location.address.formatted}}</em></p>

  <!-- Show all images: -->
  {{#each images}}
    <!-- Image fields "thumbnail", "medium", "full" -->
    <!-- are available in this block                -->
    <img src="{{thumbnail}}" />
  {{/each}}

{{/each}}


<!-- Region Details View -->
<!-- Region fields are available in this template -->
<h5>{{#if title}} {{title}} {{else}} {{id}} {{/if}}</h5>
<p>Status: {{status_text}}</p>

<!-- Show all linked Database Objects: -->
{{#each objects}}

  <!-- DB Object are available inside of this block -->

  <h5>{{title}}</h5>
  <!-- When you need to render a field as HTML, use 3 curly braces instead of 2:-->
  <p>{{{description}}}</p>
  <p><em>{{location.address.formatted}}</em></p>

  <!-- Show all images: -->
  {{#each images}}
    <!-- Image fields "thumbnail", "medium", "full" -->
    <!-- are available in this block                -->
    <img src="{{thumbnail}}" />
  {{/each}}

{{/each}}


<!-- DB Object Popover -->
<!-- DB Object fields are available in this template. -->
<h5>{{title}}</h5>
<!-- When you need to render a fields as HTML, use 3 curly braces instead of 2:-->
<p>{{{description}}}</p>
<p><em>{{location.address.formatted}}</em></p>

<!-- Show all images: -->
{{#each images}}
  <!-- Image fields "thumbnail", "medium", "full" -->
  <!-- are available in this block                -->
  <img src="{{thumbnail}}" />
{{/each}}

<!-- Show all linked Regions, comma-separated: -->
<p> Regions: 
  {{#each regions}}
    <!-- Region fields are available in this block -->
    {{#if title}}
      {{title}}
    {{else}}
      {{id}}
    {{/if}}{{#unless @last}}, {{/unless}}
  {{/each}}
</p>