const metodoPago = document.getElementById('metodoPago');
const tarjetaFields = document.getElementById('tarjetaFields');
const transferenciaFields = document.getElementById('transferenciaFields');
const efectivoFields = document.getElementById('efectivoFields');

metodoPago.addEventListener('change', () => {
  const metodo = metodoPago.value;
  tarjetaFields.classList.add('oculto');
  transferenciaFields.classList.add('oculto');
  efectivoFields.classList.add('oculto');

  if (metodo === 'Tarjeta') tarjetaFields.classList.remove('oculto');
  if (metodo === 'Transferencia') transferenciaFields.classList.remove('oculto');
  if (metodo === 'Efectivo') efectivoFields.classList.remove('oculto');
});
