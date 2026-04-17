{* Home Page Template *}

<h1>Добро пожаловать в Abelo</h1>
<p>Блог о веб-разработке, PHP, JavaScript, и DevOps</p>

{* Statistics Section *}
<div class="stats">
    <div class="stat-card">
        <div class="number">{$totalPosts|default:0}</div>
        <div class="label">Статей</div>
    </div>
    <div class="stat-card">
        <div class="number">{$totalCategories|default:0}</div>
        <div class="label">Категорий</div>
    </div>
    <div class="stat-card">
        <div class="number">{count($posts)|default:0}</div>
        <div class="label">Последних постов</div>
    </div>
</div>

{* Latest Posts Section *}
<section>
    <h2>Последние статьи</h2>

    {if count($posts) > 0}
        <div class="posts-list">
            {foreach $posts as $post}
                <div class="post-card">
                    <h3>
                        <a href="/posts/{$post->slug}">{$post->title}</a>
                    </h3>

                    <div class="post-meta">
                        <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                        <span> | 👁️ {$post->views} просмотров</span>
                    </div>

                    <p class="post-excerpt">{$post->excerpt|default:$post->content|substr:0:150}...</p>

                    <a href="/posts/{$post->slug}" class="btn">Читать далее →</a>
                </div>
            {/foreach}
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/posts" class="btn">Все статьи</a>
        </div>
    {else}
        <p>Пока нет опубликованных статей. Скоро здесь появится интересный контент!</p>
    {/if}
</section>

{* Popular Posts Section *}
{if count($popularPosts) > 0}
    <section style="margin-top: 3rem; background-color: #fff8e1; padding: 2rem; border-radius: 0.5rem;">
        <h2>🏆 Популярные статьи</h2>
        <p style="color: #64748b; margin-bottom: 1.5rem;">Самые читаемые статьи на блоге</p>

        <div class="posts-list">
            {foreach $popularPosts as $post}
                <div class="post-card" style="border-left: 4px solid #f59e0b;">
                    <h3>
                        <a href="/posts/{$post->slug}">{$post->title}</a>
                    </h3>

                    <div class="post-meta">
                        <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                        <span> | 👁️ {$post->views} просмотров</span>
                    </div>

                    <p class="post-excerpt">{$post->excerpt|default:$post->content|substr:0:150}...</p>

                    <a href="/posts/{$post->slug}" class="btn">Читать далее →</a>
                </div>
            {/foreach}
        </div>
    </section>
{/if}

{* Categories Section *}
<section style="margin-top: 3rem;">
    <h2>Категории</h2>

    {if count($categories) > 0}
        <div class="grid">
            {foreach $categories as $category}
                <a href="/categories/{$category->slug}" class="category-card" style="text-decoration: none; color: inherit;">
                    <h3>{$category->name}</h3>
                    <p>{$category->description|default:'Статьи о ' . $category->name}</p>
                </a>
            {/foreach}
        </div>
    {else}
        <p>Категории не найдены.</p>
    {/if}
</section>

{* Featured Section *}
<section style="margin-top: 3rem; background-color: #f0f9ff; padding: 2rem; border-radius: 0.5rem;">
    <h2>О проекте</h2>
    <p>Abelo — это современный блог о веб-разработке, где мы делимся практическими советами, туториалами и статьями о PHP, JavaScript, веб-дизайне и DevOps.</p>
    <p>Присоединяйтесь к нашему сообществу разработчиков и будьте в курсе последних тенденций в веб-индустрии!</p>
</section>
