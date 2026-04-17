{* Categories Index Page *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / Категории
</div>

<h1>Все категории</h1>
<p style="color: #64748b; margin-bottom: 2rem;">Выберите категорию, чтобы увидеть связанные статьи</p>

{if count($categories) > 0}
    <div class="grid">
        {foreach $categories as $category}
            <a href="/categories/{$category->slug}" class="category-card" style="text-decoration: none; color: inherit;">
                <h3>{$category->name}</h3>
                <p>{$category->description|default:'Статьи о ' . $category->name}</p>
                <div style="margin-top: 1rem; font-size: 0.875rem; color: #3b82f6;">
                    Перейти к статьям →
                </div>
            </a>
        {/foreach}
    </div>
{else}
    <div style="background-color: #fff3cd; padding: 2rem; border-radius: 0.5rem; text-align: center;">
        <p>Категории не найдены.</p>
    </div>
{/if}
