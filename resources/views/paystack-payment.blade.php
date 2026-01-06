<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paystack Payment</title>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .response {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .transactions {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .transactions h2 {
            text-align: center;
            color: #333;
        }
        .transaction-item {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }
        .transaction-item.error {
            border-left-color: #dc3545;
        }
        .transaction-item.success {
            border-left-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Paystack Payment</h1>
        <form id="payment-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount (GHS)</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>
            <button type="submit" id="submit-button">Pay with Paystack</button>
        </form>
        <div id="response" class="response"></div>

        <div class="transactions">
            <h2>Recent Transactions</h2>
            <div id="transactions-list"></div>
        </div>
    </div>

    <script>
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const amount = document.getElementById('amount').value;

            // Initialize Paystack inline payment
            const handler = PaystackPop.setup({
                key: '{{ env("PAYSTACK_PUBLIC_KEY") }}',
                email: email,
                amount: amount * 100, // Convert to kobo
                currency: 'GHS',
                ref: 'ref_' + Math.floor((Math.random() * 1000000000) + 1),
                callback: function(response) {
                    // Show success message
                    const responseDiv = document.getElementById('response');
                    responseDiv.className = 'response success';
                    responseDiv.style.display = 'block';
                    responseDiv.innerHTML = 'Payment successful! Reference: ' + response.reference;

                    // Verify the payment on the server using the API endpoint
                    fetch('/api/verify-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            reference: response.reference
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Payment verification:', data);
                        if (data.status === 'success') {
                            // Fetch and display updated transactions
                            fetchTransactions();
                        }
                    })
                    .catch(error => {
                        console.error('Payment verification error:', error);
                    });
                },
                onClose: function() {
                    console.log('Payment window closed');
                }
            });

            handler.openIframe();
        });

        // Function to fetch and display transactions
        function fetchTransactions() {
            fetch('/api/transactions', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
            .then(response => response.json())
            .then(data => {
                const transactionsList = document.getElementById('transactions-list');
                transactionsList.innerHTML = '';

                if (data.length === 0) {
                    transactionsList.innerHTML = '<p>No transactions found.</p>';
                    return;
                }

                data.forEach(transaction => {
                    const transactionItem = document.createElement('div');
                    transactionItem.className = 'transaction-item ' + (transaction.status === 'success' ? 'success' : 'error');
                    transactionItem.innerHTML = `
                        <p><strong>Reference:</strong> ${transaction.reference}</p>
                        <p><strong>Email:</strong> ${transaction.email}</p>
                        <p><strong>Amount:</strong> ${transaction.amount} ${transaction.currency}</p>
                        <p><strong>Status:</strong> ${transaction.status}</p>
                        <p><strong>Date:</strong> ${new Date(transaction.created_at).toLocaleString()}</p>
                    `;
                    transactionsList.appendChild(transactionItem);
                });
            })
            .catch(error => {
                console.error('Error fetching transactions:', error);
            });
        }

        // Fetch transactions on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchTransactions();
        });
    </script>
</body>
</html>