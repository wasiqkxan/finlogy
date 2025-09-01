
import { useEffect, useState } from "react";
import useAuthContext from "../contexts/AuthContext";
import axiosClient from "../_api/axios";

export default function Dashboard() {
    const { user } = useAuthContext();
    const [accounts, setAccounts] = useState([]);
    const [transactions, setTransactions] = useState([]);

    useEffect(() => {
        axiosClient.get("/accounts").then(({ data }) => {
            setAccounts(data.data);
        });
        axiosClient.get("/transactions?limit=5").then(({ data }) => {
            setTransactions(data.data);
        });
    }, []);

    return (
        <div>
            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p className="text-gray-600">Welcome, {user && user.name}!</p>
                </div>
            </header>
            <div className="mt-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="p-5">
                                <h3 className="text-lg leading-6 font-medium text-gray-900">Account Balances</h3>
                                <div className="mt-5">
                                    <ul className="divide-y divide-gray-200">
                                        {accounts.map((account) => (
                                            <li key={account.id} className="py-4 flex justify-between">
                                                <p className="text-sm font-medium text-gray-900">{account.name}</p>
                                                <p className="text-sm text-gray-500">${account.balance}</p>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="p-5">
                                <h3 className="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
                                <div className="mt-5">
                                    <ul className="divide-y divide-gray-200">
                                        {transactions.map((transaction) => (
                                            <li key={transaction.id} className="py-4 flex justify-between">
                                                <div>
                                                    <p className="text-sm font-medium text-gray-900">{transaction.description}</p>
                                                    <p className="text-sm text-gray-500">{transaction.date}</p>
                                                </div>
                                                <p className={`text-sm font-medium ${transaction.type === 'expense' ? 'text-red-600' : 'text-green-600'}`}>
                                                    {transaction.type === 'expense' ? '-' : '+'}${transaction.amount}
                                                </p>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
