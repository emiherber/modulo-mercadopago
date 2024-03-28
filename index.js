//Public Key
const mercadoPago = new MercadoPago('APP_USR-81a291ea-ddeb-43cc-9abe-a40e06daa4c3', {
  locale: 'es-AR'
});

const element = document.getElementById('pay-button');

element.addEventListener("click", async function () {
  
});

async function pagar(numeroCupon) {
  const element = document.getElementById('pay-button');
  element.disabled = true;
  try {
    const response = await fetch('/modulo-mercadopago/create-preference.php?numeroCupon=' + numeroCupon);
    const data = await response.json();  
    console.log(data);
    window.location.href = data.initPoint; //redirecciona automaticamente a la url de preferenceId

    //createCheckoutButton(data.preferenceId); //crea el boton de pago con preferenceId
  } catch (error) {
    console.log(error);
    alert("Unexpected error");
  }
  element.disabled = false;
}

function createCheckoutButton(preferenceId) {
  // Initialize the checkout
  const bricksBuilder = mercadoPago.bricks();

  const renderComponent = async (bricksBuilder) => {
    if (window.checkoutButton) window.checkoutButton.unmount();
    await bricksBuilder.create(
      'wallet',
      'pay-button', // class/id where the payment button will be displayed
      {
        initialization: {
          preferenceId: preferenceId
        },
        callbacks: {
          onError: (error) => console.error(error),
          onReady: () => {}
        }
      }
    );
  };
  window.checkoutButton =  renderComponent(bricksBuilder);
}
