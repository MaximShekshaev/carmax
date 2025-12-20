<template>
  <footer class="bg-dark text-light pt-5 pb-3">
    <div class="container">
      <div class="row">

        <!-- Лого и описание -->
        <div class="col-md-4 mb-4">
          <h3 class="fw-bold text-primary mb-3">CAR<span class="text-white">MAX</span></h3>
          <p class="text-secondary small">
            Аренда автомобилей по всему Казахстану.<br>
            Быстро, удобно и надёжно. Выберите авто своей мечты уже сегодня!
          </p>
          <div class="d-flex gap-3 mt-3">
            <a href="#" class="text-light fs-5"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-light fs-5"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-light fs-5"><i class="bi bi-instagram"></i></a>
          </div>
        </div>


        <!-- Контакты -->
        <div class="col-md-5 mb-4">
          <h6 class="fw-bold mb-3">Контакты</h6>
          <p class="mb-2"><i class="bi bi-telephone me-2"></i>+7 (701) 123-45-67</p>
          <p class="mb-2"><i class="bi bi-envelope me-2"></i>support@carmax.kz</p>
          <p class="mb-2"><i class="bi bi-geo-alt me-2"></i>г. Астана, Казахстан</p>
        
        </div>

      </div>

      <hr class="border-secondary my-3">

      <div class="text-center text-secondary small">
        © 2025 CARMAX. Все права защищены.
      </div>
    </div>
  </footer>
</template>

<style scoped>
footer {
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.25);
  transition: all 0.3s ease;
}

footer:hover {
  box-shadow: 0 14px 35px rgba(0,0,0,0.3);
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

button.btn-primary {
  border-radius: 0.75rem;
  font-weight: 600;
  transition: transform 0.2s, background 0.3s;
}

button.btn-primary:hover {
  background-color: #1f5bcc;
  transform: translateY(-2px);
}

hr {
  opacity: 0.3;
}

p {
  line-height: 1.6;
}
</style>
