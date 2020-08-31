<div class="container">
    <h1>Hello admin!</h1>
<h2> Высший средний балл - {$avg_score} </h2>

<h2>Все студенты:</h2>
    {foreach from=$names key=id item=i}
        <li>{$i.name}</li>
    {/foreach}

   
</div>