<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.url) { %}
                    <a href="!{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="!{%=file.url%}_240.jpg"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name system">
                {% if (file.url) { %}
                    <a href="!{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Ошибка</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size system">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            <button class="wild_text delete">Удалить</button>
            <input type="checkbox" name="delete" value="1" class="toggle" checked=true style="display:none">
        </td>
    </tr>
{% } %}
</script>