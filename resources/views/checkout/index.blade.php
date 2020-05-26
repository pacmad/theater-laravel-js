@extends('layouts.app')

@section('extra-script')
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <h1>Page de paiement</h1>
    <div class="row">
        <div class="col-md-6">
            <form id="payment-form" class="my-3">
              <div id="card-element">
                <!-- Elements will create input elements here -->
              </div>

              <!-- We'll put the error messages in this element -->
              <div id="card-errors" role="alert"></div>

              <button class="btn btn-success mt-3" id="submit">Procéder au paiement</button>
            </form>
        </div>
    </div>
    
    
@endsection

@section('extra-js')
<script>
    var stripe = Stripe('pk_test_Jy9tprS4EW8IkVha62iekomP00jXl78c7q');
    var elements = stripe.elements();
    var style = {
        base: {
          color: "#32325d",
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: "antialiased",
          fontSize: "16px",
          "::placeholder": {
            color: "#aab7c4"
          }
        },
        invalid: {
          color: "#fa755a",
          iconColor: "#fa755a"
        }
    };
    
    var card = elements.create("card", { style: style });
    card.mount("#card-element");
    
    card.on('change', ({error}) => {
      const displayError = document.getElementById('card-errors');
        if (error) {
          displayError.textContent = error.message;
        } else {
          displayError.textContent = '';
        }
    });
    
    // Soumission du formulaire
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(ev) {
        ev.preventDefault();
        stripe.confirmCardPayment("{{ $clientSecret }}", {
        payment_method: {
            card: card,
            /*
            billing_details: {
            name: 'Jenny Rosen'
            } 
            */
        }
        }).then(function(result) {
            if (result.error) {
                // Show error to your customer (e.g., insufficient funds)
                console.log(result.error.message);
            } else {
                // The payment has been processed!
                if (result.paymentIntent.status === 'succeeded') {
                // Show a success message to your customer
                // There's a risk of the customer closing the window before callback
                // execution. Set up a webhook or plugin to listen for the
                // payment_intent.succeeded event that handles any business critical
                // post-payment actions.
                console.log(result.paymentIntent);
                }
            }
        });
    });
</script>
@endsection