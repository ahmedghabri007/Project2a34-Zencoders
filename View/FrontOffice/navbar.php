<div class="container-fluid position-relative p-0">
    <!-- Barre de navigation améliorée -->
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0" style="background-color: #061429;">
        <div class="container">
            <a href="index.php" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Startup</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Accueil</a>
                    <a href="events.php" class="nav-item nav-link">Événements</a>
                    <a href="login.php" class="nav-item nav-link">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

    
</div>

<style>
    /* Styles pour la nouvelle bannière hero */
    .hero-header {
        height: 80vh;
        min-height: 500px;
        position: relative;
        overflow: hidden;
    }
    
    .hero-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        z-index: 1;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2;
    }
    
    .hero-content {
        position: relative;
        z-index: 3;
        padding-top: 8rem;
    }
    
    @media (max-width: 768px) {
        .hero-header {
            height: 60vh;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
        }
    }
</style>