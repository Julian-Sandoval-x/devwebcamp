<h2 class="pagina__heading"><?php echo $titulo; ?></h2>
<p class="pagina__descripcion">Elige hasta a 5 eventos para asistir de forma presencial.</p>

<div class="evento-registro">
    <main class="evento-registro__listado">
        <h3 class="evento-registro__heading--conferencias">&lt;Conferencias /></h3>
        <p class="evento-registro__fecha">Viernes 18 de Julio</p>

        <div class="evento-registro__grid">
            <?php foreach ($eventos['conferencias_v'] as $evento) { ?>
                <?php include __DIR__  . "/evento.php"; ?>
            <?php } ?>
        </div>

        <p class="evento-registro__fecha">Sábado 19 de Julio</p>

        <div class="evento-registro__grid">
            <?php foreach ($eventos['conferencias_s'] as $evento) { ?>
                <?php include __DIR__  . "/evento.php"; ?>
            <?php } ?>
        </div>

        <h3 class="evento-registro__heading--workshops">&lt;Workshops /></h3>
        <p class="evento-registro__fecha">Viernes 18 de Julio</p>

        <div class="evento-registro__grid eventos--workshops">
            <?php foreach ($eventos['workshops_v'] as $evento) { ?>
                <?php include __DIR__  . "/evento.php"; ?>
            <?php } ?>
        </div>

        <p class="evento-registro__fecha">Sábado 19 de Julio</p>

        <div class="evento-registro__grid eventos--workshops">
            <?php foreach ($eventos['workshops_s'] as $evento) { ?>
                <?php include __DIR__  . "/evento.php"; ?>
            <?php } ?>
        </div>
    </main>

    <aside class="registro">
        <h2 class="registro__heading">Tu registro</h2>

        <div id="registro-resumen" class="registro_resumen"></div>

        <div class="registro__regalo">
            <label for="regalo" class="registro__label">Selecciona un Regalo</label>
            <select id="regalo" class="registro__select">
                <option value="">--Selecciona tu Regalo--</option>
                <?php foreach ($regalos as $regalo) { ?>
                    <option value="<?php echo $regalo->id; ?>"><?php echo $regalo->nombre; ?></option>
                <?php } ?>
            </select>
        </div>

        <form action="" id="registro" class="formulario">
            <div class="formulario__campo">
                <input type="submit" class="formulario__submit formulario__submit--full" value="Registrar">
            </div>
        </form>

    </aside>

</div>