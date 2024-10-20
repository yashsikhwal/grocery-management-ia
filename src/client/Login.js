import React, { useState } from 'react';
import axios from 'axios';
import AdminPage from './AdminPage'
import UserPage from './UserPage'
import NewUser from './NewUser'

const Login = () => {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');
    const [loginType, setLoginType] = useState('');
    const [newUser, setNewUser] = useState(false);

    const handleLogin = async (e) => {
        e.preventDefault();
        setMessage('');
        try 
        {
            const response = await axios.post('http://localhost/grocery-server/login.php', {
                username,
                password
            });
            if(response.data.message == "AdminLoginSuccess")
                setLoginType('admin');
            else if(response.data.message == "UserLoginSuccess")
                setLoginType('user');    
            setMessage(response.data.message);
        } 
        catch (error)
        {
            setMessage('Error logging in');
        }
    };

    const handleNewUserClick = () => {
        setNewUser(true);
    };

    // Render Admin/User Login
    if (loginType === 'admin') 
        return <AdminPage />;
    else if (loginType === 'user') 
        return <UserPage />;

    // Render New User Login
    if(newUser)
        return <NewUser />

    return (
        <div>
            <h2>Login</h2>
            <form onSubmit={handleLogin}>
                <div>
                    <label>Username:</label>
                    <input
                        type="text"
                        value={username}
                        onChange={(e) => setUsername(e.target.value)}
                        required
                    />
                </div>
                <div>
                    <label>Password:</label>
                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                </div>
                <button type="submit">Login</button>
                <button type="button" onClick={handleNewUserClick}>New User</button>
                
            </form>
            {message && <p>{message}</p>}
        </div>
    );
};

export default Login;