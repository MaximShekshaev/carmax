<template>
  <footer class="footer">
    <div class="container py-5">

      <div class="row gy-4">

        <div class="col-lg-4">
          <h3 class="footer-logo">
            CAR<span>MAX</span>
          </h3>
          <p class="footer-text">
            Аренда автомобилей по всему Казахстану.<br>
            Быстро. Надёжно. Безупречно.
          </p>

          <div class="socials mt-4">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
          </div>
        </div>

        <div class="col-lg-4">
          <h6 class="footer-title">Контакты</h6>
          <ul class="footer-list">
            <li><i class="bi bi-telephone"></i> +7 (701) 123-45-67</li>
            <li><i class="bi bi-envelope"></i> support@carmax.kz</li>
            <li><i class="bi bi-geo-alt"></i> г. Астана, Казахстан</li>
          </ul>
        </div>

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
        © 2026 <strong>CARMAX</strong>. Все права защищены.
      </div>

    </div>
  </footer>
</template>


<style scoped>
/* СВЕТЛЫЙ МИНИМАЛИСТИЧНЫЙ ФОН */
.footer {
  background-color: #ffffff;
  color: #4b5563;
  border-top: 1px solid rgba(0, 0, 0, 0.06);
  font-size: 0.95rem;
}

/* ЛОГОТИП (В тон к шапке) */
.footer-logo {
  font-size: 1.6rem;
  font-weight: 900;
  letter-spacing: 1.5px;
  color: #111827;
  text-transform: uppercase;
  margin-bottom: 16px;
}

.footer-logo span {
  color: #6b7280;
  font-weight: 400;
}

.footer-text {
  line-height: 1.6;
  max-width: 320px;
  color: #6b7280;
}

/* ЗАГОЛОВКИ БЛОКОВ */
.footer-title {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 20px;
  color: #111827;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

/* СПИСКИ И ССЫЛКИ */
.footer-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-list li {
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 12px;
  color: #4b5563;
}

.footer-list a {
  color: #4b5563;
  text-decoration: none;
  transition: color 0.25s ease;
}

.footer-list a:hover {
  color: #111827;
}

/* Иконки в контактах */
.footer-list i {
  color: #9ca3af;
  font-size: 1.1rem;
}

/* СОЦИАЛЬНЫЕ СЕТИ (Круглые минималистичные кнопки) */
.socials {
  display: flex;
  gap: 12px;
}

.socials a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  color: #4b5563;
  border: 1px solid #e5e7eb;
  border-radius: 50%;
  font-size: 1.1rem;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.socials a:hover {
  color: #111827;
  border-color: #111827;
  background-color: #f9fafb;
  transform: translateY(-2px);
}

/* НИЖНЯЯ ЧАСТЬ */
.footer-bottom {
  text-align: center;
  padding-top: 24px;
  border-top: 1px solid rgba(0, 0, 0, 0.06);
  font-size: 0.85rem;
  color: #9ca3af;
}

.footer-bottom strong {
  color: #6b7280;
  font-weight: 600;
}
</style>