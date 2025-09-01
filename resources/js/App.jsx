
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import Dashboard from "./pages/Dashboard";
import Accounts from "./pages/Accounts";
import Transactions from "./pages/Transactions";
import Budgets from "./pages/Budgets";
import Reports from "./pages/Reports";
import Settings from "./pages/Settings";

function App() {
    return (
        <Router>
            <div>
                <nav className="bg-gray-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between h-16">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <p className="text-white">Finance Tracker</p>
                                </div>
                                <div className="hidden md:block">
                                    <div className="ml-10 flex items-baseline space-x-4">
                                        <Link to="/" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</Link>
                                        <Link to="/accounts" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Accounts</Link>
                                        <Link to="/transactions" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Transactions</Link>
                                        <Link to="/budgets" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Budgets</Link>
                                        <Link to="/reports" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Reports</Link>
                                        <Link to="/bills" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Bills</Link>
                                        <Link to="/settings" className="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Settings</Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                <main>
                    <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        <Routes>
                            <Route path="/" element={<Dashboard />} />
                            <Route path="/accounts" element={<Accounts />} />
                            <Route path="/transactions" element={<Transactions />} />
                            <Route path="/budgets" element={<Budgets />} />
                            <Route path="/reports" element={<Reports />} />
                            <Route path="/bills" element={<Bills />} />
                            <Route path="/settings" element={<Settings />} />
                        </Routes>
                    </div>
                </main>
            </div>
        </Router>
    );
}

export default App;
