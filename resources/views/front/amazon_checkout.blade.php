<html>

<body>

  <div style="text-align: center; border: 1px solid #bbb;border-radius: 3px;padding:5px;margin:5px;"><div id="AmazonPayButton"></div></div><button type="button" name="button" id="Logout">Logout</button>

  <div id="addressBookWidgetDiv" style="height:250px"></div>

  <div id="walletWidgetDiv" style="height:250px"></div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script type='text/javascript'>

    // get access token

    function getURLParameter(name, source) {

        return decodeURIComponent((new RegExp('[?|&amp;|#]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(source) || [, ""])[1].replace(/\+/g, '%20')) || null;

    }



    var accessToken = getURLParameter("access_token", location.hash);

    if (typeof accessToken === 'string' && accessToken.match(/^Atza/)) {

        document.cookie = "amazon_Login_accessToken=" + accessToken + ";path=/;secure";

    }



    window.onAmazonLoginReady = function() {

      

      amazon.Login.setClientId("{{$gs->amazon_client_id}}");

    };



    window.onAmazonPaymentsReady = function() {

      showLoginButton();

      showAddressBookWidget();

      

    };



    document.getElementById('Logout').onclick = function() {

      amazon.Login.logout();

      document.cookie = "amazon_Login_accessToken=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";

      window.location.href = '/';

    };

  </script>



  <script type='text/javascript'>

    var orderReferenceId = '';

    function showLoginButton() {

        var authRequest;

        OffAmazonPayments.Button("AmazonPayButton", "{{$gs->amazon_seller_id}}", {

          type:  "PwA",

          color: "Gold",

          size:  "medium",



          authorization: function() {

            loginOptions = {scope: "profile payments:widget payments:shipping_address", popup: true};

            authRequest = amazon.Login.authorize (loginOptions, "callback.html");

          }

        });

    }



    function showAddressBookWidget() {

        // AddressBook

        new OffAmazonPayments.Widgets.AddressBook({

          sellerId: '{{$gs->amazon_seller_id}}',

           

          onReady: function (orderReference) {

               orderReferenceId = orderReference.getAmazonOrderReferenceId();

              var el;

              if ((el = document.getElementById("orderReferenceId"))) {

                el.value = orderReferenceId;

                alert(orderReferenceId)

                

              }

              document.getElementById("reference_id").value = orderReferenceId;

              // Wallet

              showWalletWidget(orderReferenceId);

          },

          onAddressSelect: function (orderReference) {

//              $.ajax({

// 				url: '/checkout/calculate_shipping',

// 				type: 'post',

// 				data: { referenceId: orderReferenceId }

// 			}).success(function(response){

// 				// do something with the response...

// 				// like display shipping or taxes to the customer

// 				alert("ajax call worked")

// 			});



            new OffAmazonPayments.Widgets.Wallet({

				sellerId: '{{$gs->amazon_seller_id}}',

				amazonOrderReferenceId: orderReferenceId,

				displayMode: 'Edit',

				design: { size: { width: 600, height: 250 } },

				onPaymentSelect: function(orderReference){

					// enable "submit order" button

					$('#submit-order').prop('disable', false);

					

				}

			}).bind("AmazonWalletWidget");

          },

          design: {

              designMode: 'responsive'

          },

          onError: function (error) {

              // Error handling code 

              // We also recommend that you implement an onError handler in your code. 

              // @see https://payments.amazon.com/documentation/lpwa/201954960

              console.log('OffAmazonPayments.Widgets.AddressBook', error.getErrorCode(), error.getErrorMessage());

              switch (error.getErrorCode()) {

                case 'AddressNotModifiable':

                    // You cannot modify the shipping address when the order reference is in the given state.

                    break;

                case 'BuyerNotAssociated':

                    // The buyer is not associated with the given order reference. 

                    // The buyer must sign in before you render the widget.

                    break;

                case 'BuyerSessionExpired':

                    // The buyer's session with Amazon has expired. 

                    // The buyer must sign in before you render the widget.

                    break;

                case 'InvalidAccountStatus':

                    // Your merchant account is not in an appropriate state to execute this request. 

                    // For example, it has been suspended or you have not completed registration.

                    break;

                case 'InvalidOrderReferenceId':

                    // The specified order reference identifier is invalid.

                    break;

                case 'InvalidParameterValue':

                    // The value assigned to the specified parameter is not valid.

                    break;

                case 'InvalidSellerId':

                    // The merchant identifier that you have provided is invalid. Specify a valid SellerId.

                    break;

                case 'MissingParameter':

                    // The specified parameter is missing and must be provided.

                    break;

                case 'PaymentMethodNotModifiable':

                    // You cannot modify the payment method when the order reference is in the given state.

                    break;

                case 'ReleaseEnvironmentMismatch':

                    // You have attempted to render a widget in a release environment that does not match the release environment of the Order Reference object. 

                    // The release environment of the widget and the Order Reference object must match.

                    break;

                case 'StaleOrderReference':

                    // The specified order reference was not confirmed in the allowed time and is now canceled. 

                    // You cannot associate a payment method and an address with a canceled order reference.

                    break;

                case 'UnknownError':

                    // There was an unknown error in the service.

                    break;

                default:

                    // Oh My God, What's going on?

              }

          }

        }).bind("addressBookWidgetDiv");

    }



    function showWalletWidget(orderReferenceId) {

        // Wallet

        new OffAmazonPayments.Widgets.Wallet({

          sellerId: '{{$gs->amazon_seller_id}}',

          amazonOrderReferenceId: orderReferenceId,

          onReady: function(orderReference) {

              var formData = localStorage.getItem('formData');

              $('#formData').val(formData);

              console.log(orderReference.getAmazonOrderReferenceId());

          },

          onPaymentSelect: function() {

              console.log(arguments);

          },

          design: {

              designMode: 'responsive'

          },

          onError: function(error) {

              // Error handling code 

              // We also recommend that you implement an onError handler in your code. 

              // @see https://payments.amazon.com/documentation/lpwa/201954960

              console.log('OffAmazonPayments.Widgets.Wallet', error.getErrorCode(), error.getErrorMessage());

          }

        }).bind("walletWidgetDiv");

    }



    

  </script>

  <script type="text/javascript" 

    src="https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js" 

     async></script>

     <form method="post" action="{{ route('amazon-checkout',$storename) }}">

        {{csrf_field() }}

        <input type='hidden' name='formData' id='formData' />

     <input type="hidden" name="access_token" value="<?= $_GET['access_token'] ?>">

     <input type="hidden" name="reference_id" id="reference_id">

     <button id="submit-order">submit</button>

     </form>

</body>

</html>