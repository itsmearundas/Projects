const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const bodyParser = require('body-parser');
const http = require('http');
const { Server } = require('socket.io');
const app = express();
app.use(cors());
app.use(bodyParser.json());

const server = http.createServer(app);

// Database connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'gd_platform',
});

db.connect(err => {
    if (err) {
        console.error('Error connecting to MySQL:', err);
    } else {
        console.log('Connected to MySQL!');
    }
});

const io = new Server(server, {
    cors: {
        origin: "http://localhost:5000",
        methods: ["GET", "POST"],
    },
});

io.on('connection', (socket) => {
    console.log('A user connected:', socket.id);

    socket.on('joinGroup', (groupid) => {
        socket.join(`group_${groupid}`);
        console.log(`User joined group_${groupid}`);
    });

    socket.on('sendMessage', (data) => {
        const { groupid, userid, message, sender } = data;
    
        // Validate membership
        const query = `
            SELECT * FROM group_members 
            WHERE groupid = ? AND userid = ?
        `;
    
        db.query(query, [groupid, userid], (err, results) => {
            if (err) {
                console.error('Database Error:', err);
                return;
            }
    
            if (results.length === 0) {
                console.error('User not part of this group');
                return;
            }
    
            // Insert the message
            db.query(
                'INSERT INTO group_messages (groupid, userid, message, created_at) VALUES (?, ?, ?, NOW())',
                [groupid, userid, message],
                (err) => {
                    if (err) {
                        console.error('Database Error:', err);
                    } else {
                        io.to(`group_${groupid}`).emit('receiveMessage', {
                            message,
                            sender,
                            created_at: new Date().toISOString(),
                        });
                    }
                }
            );
        });
    });
    

    socket.on('disconnect', () => {
        console.log('A user disconnected:', socket.id);
    });
});

server.listen(5000, () => {
    console.log('Server is running on port 5000');
});

// Fetch all groups
app.get('/api/groups', (req, res) => {
    db.query('SELECT * FROM groups', (err, results) => {
        if (err) {
            console.error('Database Error:', err);
            return res.status(500).json({ message: 'Error fetching groups' });
        }
        res.json(results);
    });
});

// Fetch all users
app.get('/api/users', (req, res) => {
    db.query('SELECT * FROM users', (err, results) => {
        if (err) {
            res.status(500).send(err);
        } else {
            res.json(results);
        }
    });
});

// Handle user registration
app.post('/api/user_registration', (req, res) => {
    const { name, email, password } = req.body;

    if (!name || !email || !password) {
        return res.status(400).json({ message: 'All fields are required.' });
    }

    db.query('SELECT * FROM users WHERE email = ?', [email], (err, results) => {
        if (err) {
            console.error('Database Error:', err);
            return res.status(500).send(err);
        }

        if (results.length > 0) {
            return res.status(400).json({ message: 'Email already exists.' });
        }

        db.query(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)',
            [name, email, password],
            (err, result) => {
                if (err) {
                    console.error('Database Error:', err);
                    return res.status(500).send(err);
                }
                res.json({
                    message: 'User registered successfully.',
                    user: {
                        id: result.insertId,
                        name,
                        email,
                    },
                });
            }
        );
    });
});

// Handle user login
app.post('/api/user_login', (req, res) => {
    const { email, password } = req.body;

    db.query(
        "SELECT id, name, email, created_at FROM users WHERE email = ? AND password = ?",
        [email, password],
        (err, results) => {
            if (err) {
                console.error('Database Error:', err);
                return res.status(500).json({ message: 'Error logging in.' });
            }

            if (results.length === 0) {
                return res.status(401).json({ message: 'Invalid credentials.' });
            }

            res.json({ user: results[0] });
        }
    );
});

// Handle admin login
app.post('/api/admin_login', (req, res) => {
    const { email, password } = req.body;

    if (!email || !password) {
        return res.status(400).json({ message: 'Email and password are required.' });
    }

    db.query(
        'SELECT * FROM admins WHERE email = ? AND password = ?',
        [email, password],
        (err, results) => {
            if (err) {
                console.error('Database Error:', err);
                return res.status(500).send(err);
            }

            if (results.length > 0) {
                res.json({
                    message: 'Admin login successful!',
                    admin: {
                        id: results[0].id,
                        name: results[0].name,
                        email: results[0].email,
                    },
                });
            } else {
                res.status(401).json({ message: 'Invalid admin credentials.' });
            }
        }
    );
});

// Group creation endpoint



// Endpoint to create a group
app.post('/api/create_group', (req, res) => {
    const { groupid, groupname, number_of_members } = req.body;

    if (!groupid || !groupname || !number_of_members) {
        return res.status(400).json({ message: 'All fields are required.' });
    }

    db.beginTransaction(err => {
        if (err) {
            console.error('Error starting transaction:', err);
            return res.status(500).json({ message: 'Internal server error.' });
        }

        // Insert group into `groups` table
        const insertGroupQuery = `
            INSERT INTO groups (groupid, groupname, number_of_members)
            VALUES (?, ?, ?)
        `;
        db.query(insertGroupQuery, [groupid, groupname, number_of_members], (err, groupResult) => {
            if (err) {
                console.error('Error inserting into groups table:', err);
                return db.rollback(() => {
                    res.status(500).json({ message: 'Failed to create group.' });
                });
            }

            console.log('Group created successfully:', groupResult.insertId);

            // Fetch all user IDs
            const fetchUsersQuery = 'SELECT id FROM users';
            db.query(fetchUsersQuery, (err, usersResult) => {
                if (err) {
                    console.error('Error fetching user IDs:', err);
                    return db.rollback(() => {
                        res.status(500).json({ message: 'Failed to fetch users.' });
                    });
                }

                const userIds = usersResult.map(user => user.id);

                if (number_of_members > userIds.length) {
                    return db.rollback(() => {
                        res.status(400).json({
                            message: `Requested members (${number_of_members}) exceed available users (${userIds.length}).`,
                        });
                    });
                }

                // Select random user IDs
                const selectedUsers = [];
                while (selectedUsers.length < number_of_members) {
                    const randomIndex = Math.floor(Math.random() * userIds.length);
                    const userId = userIds[randomIndex];
                    if (!selectedUsers.includes(userId)) {
                        selectedUsers.push(userId);
                    }
                }

                console.log('Selected User IDs:', selectedUsers);

                // Insert into `group_members` table
                const insertGroupMembersQuery = `
                    INSERT INTO group_members (groupid, userid)
                    VALUES ?
                `;
                const groupMembersData = selectedUsers.map(userId => [groupid, userId]);

                db.query(insertGroupMembersQuery, [groupMembersData], (err, membersResult) => {
                    if (err) {
                        console.error('Error inserting into group_members table:', err);
                        return db.rollback(() => {
                            res.status(500).json({ message: 'Failed to create group members.' });
                        });
                    }

                    console.log('Group members added:', membersResult.affectedRows);

                    // Commit the transaction
                    db.commit(err => {
                        if (err) {
                            console.error('Error committing transaction:', err);
                            return db.rollback(() => {
                                res.status(500).json({ message: 'Failed to create group.' });
                            });
                        }

                        res.status(201).json({
                            message: 'Group created successfully!',
                            group: {
                                groupid,
                                groupname,
                                number_of_members,
                                selected_user_ids: selectedUsers,
                            },
                        });
                    });
                });
            });
        });
    });
});




// Get total count of groups
app.get('/api/group_count', (req, res) => {
    db.query('SELECT COUNT(*) AS totalGroups FROM groups', (err, results) => {
        if (err) {
            console.error('Database Error:', err);
            return res.status(500).json({ message: 'Error fetching group count' });
        }
        res.json(results[0]);
    });
});

// Fetch user details
app.get('/api/user_details/:userId', (req, res) => {
    const userId = req.params.userId;

    db.query(
        'SELECT id, name, email, created_at FROM users WHERE id = ?',
        [userId],
        (err, results) => {
            if (err) {
                return res.status(500).json({ message: 'Error fetching user details.' });
            }

            if (results.length === 0) {
                return res.status(404).json({ message: 'User not found.' });
            }

            res.json(results[0]);
        }
    );
});

// Fetch groups for a user
app.get('/api/user_groups/:userId', (req, res) => {
    const userId = req.params.userId;

    db.query(
        `SELECT g.groupid, g.groupname, g.number_of_members
         FROM groups g
         INNER JOIN group_members gm ON g.groupid = gm.groupid
         WHERE gm.userid = ?`,
        [userId],
        (err, results) => {
            if (err) {
                console.error('Database Error:', err);
                return res.status(500).json({ message: 'Error fetching user groups.' });
            }

            if (results.length === 0) {
                return res.status(404).json({ message: 'No groups found for this user.' });
            }

            res.json({ groups: results });
        }
    );
});

// Fetch group messages
// Fetch group messages for a specific group and user
// Endpoint to fetch messages for a specific group
// Endpoint to fetch messages for a specific group and user
// Assuming you are using MySQL and Express.js


app.get('/api/group_messages/:groupid', (req, res) => {
    const { groupid } = req.params;

    const query = `
        SELECT gm.message, gm.created_at, u.name AS sender
        FROM group_messages gm
        JOIN users u ON gm.userid = u.id
        WHERE gm.groupid = ?
        ORDER BY gm.created_at ASC
    `;

    db.query(query, [groupid], (err, results) => {
        if (err) {
            console.error('Error fetching messages:', err);
            return res.status(500).json({ message: 'Error fetching messages.' });
        }

        res.json(results);
    });
});


  
app.post('/api/group_messages', (req, res) => {
    const { groupid, userid, message } = req.body;

    if (!groupid || !userid || !message) {
        return res.status(400).json({ message: "Group ID, User ID, and Message are required." });
    }

    const query = `
        INSERT INTO group_messages (groupid, userid, message, created_at)
        VALUES (?, ?, ?, NOW())
    `;

    db.query(query, [groupid, userid, message], (err) => {
        if (err) {
            console.error('Error inserting message:', err);
            return res.status(500).json({ message: "Database error." });
        }

        io.to(`group_${groupid}`).emit('receiveMessage', {
            groupid,
            userid,
            message,
            created_at: new Date().toISOString(),
        });

        res.status(201).json({ message: "Message sent successfully." });
    });
});


// Handle unmatched routes
app.use((req, res) => {
    res.status(404).send('Route not found');
});

// Start the server
const PORT = 3003;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
