<main class="devwebcamp">
    <h2 class="devwebcamp__heading"><?php echo $titulo; ?></h2>
    <p class="devwebcamp__descripcion">Conoce la conferencia más importante de latinoamérica</p>
    <div class="devwebcamp__grid">
        <div <?php aos_animacion(); ?> class="devwebcamp__imagen">
            <picture>
                <source srcset="build/img/sobre_devwebcamp.avif" type="image/avif">
                <source srcset="build/img/sobre_devwebcamp.webp" type="image/webp">
                <img loading="lazy" width="200" height="200" src="build/img/sobre_devwebcamp.jpg" alt="Imagen DevWebCamp">
            </picture>
        </div>

        <div <?php aos_animacion(); ?> class="devwebcamp__contenido">
            <p <?php aos_animacion(); ?> class="devwebcamp__texto">
                Aliquam eget nulla varius eros commodo tristique accumsan vel augue. Maecenas dolor lacus, rutrum sed egestas in, pharetra ut sapien. Proin ac dictum magna. Nullam sed augue nulla. Phasellus ac ipsum consequat ante ultricies condimentum at sit amet est. Aliquam sed ullamcorper sapien, sed pretium erat. In ac dolor volutpat, egestas est ac, lobortis sapien.
            </p>
            <p <?php aos_animacion(); ?> class="devwebcamp__texto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean id nisl ac justo consequat vehicula quis sit amet massa. Proin scelerisque lacus hendrerit tincidunt malesuada. Nam gravida augue accumsan, mattis tortor a, convallis metus. Sed sollicitudin dapibus sapien, eget finibus tortor. Nullam vel orci aliquet, tincidunt enim ac, placerat erat. In sit amet iaculis ipsum, ut maximus ex.
            </p>
        </div>
    </div>
</main>