<template>
  <footer class="footer">
    <div class="container py-5">

      <div class="row gy-4">

        <!-- ЛОГО + ОПИСАНИЕ -->
        <div class="col-lg-4">
          <h3 class="footer-logo">
            CAR<span>MAX</span>
          </h3>
          <p class="footer-text">
            Аренда автомобилей по всему Казахстану.<br>
            Быстро. Надёжно. По-мужски.
          </p>

          <div class="socials mt-3">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
          </div>
        </div>

        <!-- КОНТАКТЫ -->
        <div class="col-lg-4">
          <h6 class="footer-title">Контакты</h6>
          <ul class="footer-list">
            <li><i class="bi bi-telephone"></i> +7 (701) 123-45-67</li>
            <li><i class="bi bi-envelope"></i> support@carmax.kz</li>
            <li><i class="bi bi-geo-alt"></i> г. Астана, Казахстан</li>
          </ul>
        </div>

        <!-- НАВИГАЦИЯ -->
        <div class="col-lg-4">
          <h6 class="footer-title">Навигация</h6>
          <ul class="footer-list">
            <li><router-link to="/">Главная</router-link></li>
            <li><router-link to="/login">Вход</router-link></li>
            <li><router-link to="/register">Регистрация</router-link></li>
            <li><router-link to="/my-rentals">Моя аренда</router-link></li>
          </ul>
        </div>

      </div>

      <div class="footer-bottom mt-5">
        © 2025 <strong>CARMAX</strong>. Все права защищены.
      </div>

    </div>
  </footer>
</template>


<style scoped>
.footer {
  background: radial-gradient(circle at top, #1b1f27, #0d0f14);
  color: #9ca3af;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);
}


.footer-logo {
  font-size: 1.8rem;
  font-weight: 900;
  letter-spacing: 1px;
  color: #2563eb;
}

.footer-logo span {
  color: #ffffff;
}


.footer-text {
  margin-top: 12px;
  line-height: 1.6;
  max-width: 320px;
}


.footer-title {
  font-weight: 700;
  margin-bottom: 16px;
  color: #ffffff;
  letter-spacing: 0.5px;
}


.footer-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-list li {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.footer-list a {
  color: #9ca3af;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-list a:hover {
  color: #3b82f6;
}


.footer-list i {
  color: #3b82f6;
}


.socials {
  display: flex;
  gap: 16px;
}

.socials a {
  color: #9ca3af;
  font-size: 1.3rem;
  transition: all 0.3s ease;
}

.socials a:hover {
  color: #3b82f6;
  transform: translateY(-3px);
}


.footer-bottom {
  text-align: center;
  padding-top: 24px;
  border-top: 1px solid rgba(255,255,255,0.08);
  font-size: 0.9rem;
  color: #6b7280;
}
</style>


