<?php
    require 'config/config.php';
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furni Store</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

</head>

<body>

<?php require 'includes/header.php'; ?>

<section id="page-header" class="bg-gradient-to-r from-blue-400 to-purple-600 text-white py-8 md:py-24">
    <h2 class="text-5xl font-bold text-center"><strong>Conócenos</strong></h2>
    <p class="text-center text-lg max-w-md mx-auto">Te damos la bienvenida a Furni Store</p>
</section>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<section class="history_area section_padding_130">
    <div class="container mx-auto py-12 md:py-24">
        <div class="row justify-center">
            <div class="col-12 col-sm-8 col-lg-6">
                <!-- Section Heading-->
                <div class="section_heading text-center">
                    <h6 class="text-2xl font-bold tracking-wide uppercase">Nuestra historia</h6>
                    <h3 class="text-5xl font-extrabold tracking-tightest">Una breve historia de nuestra tienda en línea</h3>
                    <div class="line mx-auto my-6"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Timeline Area-->
                <div class="apland-timeline-area">
                    <!-- Single Timeline Content-->
                    <div class="single-timeline-area">
                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                            <p>2022</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-shopping-bag" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Furni Store tienda de muebles nace</h6>
                                        <p>Fundada en el 2022, Furni Store tienda de muebles es una tienda en línea que busca ofrecer una gran variedad de muebles de calidad.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-users" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Equipo de trabajo</h6>
                                        <p>En el 2022, Furni Store tienda de muebles cuenta con un equipo de trabajo comprometido y apasionado por brindar una gran variedad de muebles de calidad.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-lock" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Seguridad</h6>
                                        <p>En el 2022, Furni Store tienda de muebles se compromete a brindar una tienda en línea segura y confiable para que puedan realizar sus compras con tranquilidad.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single Timeline Content-->
                    <div class="single-timeline-area">
                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                            <p>2023</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-users" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Equipo de trabajo</h6>
                                        <p>En el 2023, Furni Store tienda de muebles cuenta con un equipo de trabajo comprometido y apasionado por brindar una gran variedad de muebles de calidad.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-globe" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Expansión Internacional</h6>
                                        <p>En el 2023, Furni Store tienda de muebles comienza su expansión internacional, llevando sus productos a nuevos mercados y expandiendo su presencia global.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-lock" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Seguridad</h6>
                                        <p>En el 2023, Furni Store tienda de muebles se compromete a brindar una tienda en línea segura y confiable para que puedan realizar sus compras con tranquilidad.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single Timeline Content-->
                    <div class="single-timeline-area">
                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                            <p>2024</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-award" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Reconocimientos</h6>
                                        <p>En el 2024, Furni Store tienda de muebles recibe múltiples reconocimientos por su innovación y compromiso con la calidad en muebles.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-lock" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>Seguridad</h6>
                                        <p>En el 2024, Furni Store tienda de muebles se compromete a brindar una tienda en línea segura y confiable para que puedan realizar sus compras con tranquilidad.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fas fa-mobile-alt" style="color: #2196F3;"></i></div>
                                    <div class="timeline-text">
                                        <h6>App móvil</h6>
                                        <p>En el 2024, Furni Store tienda de muebles lanza su aplicación móvil para que puedan acceder a sus muebles desde cualquier lugar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .history_area {
        position: relative;
        z-index: 1;
    }
    .single-timeline-area {
        position: relative;
        z-index: 1;
        padding-left: 180px;
    }
    @media only screen and (max-width: 575px) {
        .single-timeline-area {
            padding-left: 100px;
        }
    }
    .single-timeline-area .timeline-date {
        position: absolute;
        width: 180px;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -ms-grid-row-align: center;
        align-items: center;
        -webkit-box-pack: end;
        -ms-flex-pack: end;
        justify-content: flex-end;
        padding-right: 60px;
    }
    @media only screen and (max-width: 575px) {
        .single-timeline-area .timeline-date {
            width: 100px;
        }
    }
    .single-timeline-area .timeline-date::after {
        position: absolute;
        width: 3px;
        height: 100%;
        content: "";
        background-color: #ebebeb;
        top: 0;
        right: 30px;
        z-index: 1;
    }
    .single-timeline-area .timeline-date::before {
        position: absolute;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background-color: #2196F3;
        content: "";
        top: 50%;
        right: 26px;
        z-index: 5;
        margin-top: -5.5px;
    }
    .single-timeline-area .timeline-date p {
        margin-bottom: 0;
        color: #020710;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 500;
    }
    .single-timeline-area .single-timeline-content {
        position: relative;
        z-index: 1;
        padding: 30px 30px 25px;
        border-radius: 6px;
        margin-bottom: 15px;
        margin-top: 15px;
        -webkit-box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
        box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
        border: 1px solid #ebebeb;
    }
    @media only screen and (max-width: 575px) {
        .single-timeline-area .single-timeline-content {
            padding: 20px;
        }
    }
    .single-timeline-area .single-timeline-content .timeline-icon {
        -webkit-transition-duration: 500ms;
        transition-duration: 500ms;
        width: 30px;
        height: 30px;
        background-color: #2196F3;
        -webkit-box-flex: 0;
        -ms-flex: 0 0 30px;
        flex: 0 0 30px;
        text-align: center;
        max-width: 30px;
        border-radius: 50%;
        margin-right: 15px;
    }
    .single-timeline-area .single-timeline-content .timeline-icon i {
        color: #ffffff;
        line-height: 30px;
    }
    .single-timeline-area .single-timeline-content .timeline-text h6 {
        -webkit-transition-duration: 500ms;
        transition-duration: 500ms;
    }
    .single-timeline-area .single-timeline-content .timeline-text p {
        font-size: 13px;
        margin-bottom: 0;
    }
    .single-timeline-area .single-timeline-content:hover .timeline-icon,
    .single-timeline-area .single-timeline-content:focus .timeline-icon {
        background-color: #020710;
    }
    .single-timeline-area .single-timeline-content:hover .timeline-text h6,
    .single-timeline-area .single-timeline-content:focus .timeline-text h6 {
        color: #3f43fd;
    }
</style>


<?php require 'includes/footer.php'; ?>

    <script src="script.js"></script>
</body>

</html>