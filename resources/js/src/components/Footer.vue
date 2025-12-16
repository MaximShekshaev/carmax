<template>
  <footer class="bg-dark text-light py-5">
    <div class="container">
      <div class="row">

        <!-- Лого и описание -->
        <div class="col-md-4 mb-4">
          <h4 class="fw-bold mb-3">CARMAX</h4>
          <p class="small text-secondary">
            Аренда автомобилей по всему Казахстану. <br>
            Быстро, удобно и надёжно. Выберите авто своей мечты уже сегодня!
          </p>
        </div>

        <!-- Навигация -->
        <div class="col-md-2 mb-4">
          <h6 class="fw-bold mb-3">Навигация</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="footer-link">Главная</a></li>
            <li><a href="#" class="footer-link">Моя аренда</a></li>
            <li><a href="#" class="footer-link">Контакты</a></li>
          </ul>
        </div>

        <!-- Контакты -->
        <div class="col-md-3 mb-4">
          <h6 class="fw-bold mb-3">Контакты</h6>
          <p class="mb-2"><i class="bi bi-telephone me-2"></i> +7 777 123 45 67</p>
          <p class="mb-2"><i class="bi bi-envelope me-2"></i> support@carmax.kz</p>
          <p class="mb-2"><i class="bi bi-geo-alt me-2"></i> Алматы, Казахстан</p>
        </div>

      </div>

      <hr class="border-secondary">

      <div class="text-center text-secondary small">
        © 2025 CARMAX. Все права защищены.
      </div>
    </div>
  </footer>
</template>

<style scoped>
footer {
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.4);
  transition: all 0.3s ease;
}

footer:hover {
  box-shadow: 0 12px 30px rgba(0,0,0,0.5);
}

.footer-link {
  color: #adb5bd;
  text-decoration: none;
  transition: color 0.3s ease, transform 0.3s ease;
}

.footer-link:hover {
  color: #0d6efd;
  transform: translateX(5px);
}

hr {
  opacity: 0.3;
}

p {
  line-height: 1.6;
}
</style>
