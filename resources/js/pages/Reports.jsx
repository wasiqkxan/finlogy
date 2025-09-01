
import { useEffect, useState } from "react";
import axiosClient from "../_api/axios";
import { PieChart, Pie, Cell, LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend } from 'recharts';

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042'];

export default function Reports() {
    const [spendingByCategory, setSpendingByCategory] = useState([]);
    const [cashFlow, setCashFlow] = useState([]);

    useEffect(() => {
        axiosClient.get("/reports/spending-by-category").then(({ data }) => {
            setSpendingByCategory(data.data);
        });
        axiosClient.get("/reports/cash-flow").then(({ data }) => {
            setCashFlow(data.data);
        });
    }, []);

    return (
        <div>
            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-gray-900">Reports</h1>
                </div>
            </header>
            <div className="mt-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="bg-white overflow-hidden shadow rounded-lg p-5">
                            <h3 className="text-lg leading-6 font-medium text-gray-900">Spending by Category</h3>
                            <div className="mt-5">
                                <PieChart width={400} height={400}>
                                    <Pie
                                        data={spendingByCategory}
                                        cx={200}
                                        cy={200}
                                        labelLine={false}
                                        outerRadius={80}
                                        fill="#8884d8"
                                        dataKey="value"
                                    >
                                        {spendingByCategory.map((entry, index) => <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />)}
                                    </Pie>
                                    <Tooltip />
                                </PieChart>
                            </div>
                        </div>
                        <div className="bg-white overflow-hidden shadow rounded-lg p-5">
                            <h3 className="text-lg leading-6 font-medium text-gray-900">Cash Flow</h3>
                            <div className="mt-5">
                                <LineChart
                                    width={500}
                                    height={300}
                                    data={cashFlow}
                                >
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis dataKey="date" />
                                    <YAxis />
                                    <Tooltip />
                                    <Legend />
                                    <Line type="monotone" dataKey="income" stroke="#82ca9d" />
                                    <Line type="monotone" dataKey="expense" stroke="#8884d8" />
                                </LineChart>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
