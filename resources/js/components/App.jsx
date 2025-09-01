
import React, { useState, useEffect } from 'react';
import axios from 'axios';

function App() {
    const [accounts, setAccounts] = useState([]);

    useEffect(() => {
        axios.get('/api/accounts')
            .then(response => {
                setAccounts(response.data);
            })
            .catch(error => {
                console.error('There was an error fetching the accounts!', error);
            });
    }, []);

    return (
        <div className="container mx-auto mt-8">
            <h1 className="text-2xl font-bold mb-4">Accounts</h1>
            <ul className="list-disc pl-5">
                {accounts.map(account => (
                    <li key={account.id} className="mb-2">{account.name} - ${account.balance}</li>
                ))}
            </ul>
        </div>
    );
}

export default App;
