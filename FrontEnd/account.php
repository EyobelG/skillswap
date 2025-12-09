<?php
    include 'header.php';
?>
 <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: linear-gradient(135deg, #e9d5ff 0%, #c4b5fd 50%, #a78bfa 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(167, 139, 250, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(196, 181, 253, 0.3) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            padding: 2rem 0;
            box-shadow: 0 8px 20px rgba(91, 33, 182, 0.3);
            position: relative;
            z-index: 5;
        }

        .page-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fbbf24;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sign-out-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 2rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .sign-out-btn:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.5);
        }

        /* Main Layout */
        .main-wrapper {
            display: flex;
            flex: 1;
            width: 100%;
            position: relative;
            z-index: 1;
            padding-left: 0;
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            padding: 2rem 1rem;
            box-shadow: 4px 0 20px rgba(91, 33, 182, 0.3);
            display: flex;
            flex-direction: column;
            gap: 2rem;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .profile-card {
            text-align: center;
            padding: 1.5rem;
            background: rgba(107, 33, 168, 0.4);
            border-radius: 1rem;
            border: 2px solid rgba(251, 191, 36, 0.3);
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fbbf24;
            margin: 0 auto 1rem;
            display: block;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .upload-btn {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .profile-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fbbf24;
            margin-top: 0.5rem;
        }

        .profile-email {
            font-size: 0.875rem;
            color: #e9d5ff;
            margin-top: 0.25rem;
            word-break: break-word;
        }

        .menu-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .menu-item {
            padding: 1rem 1.25rem;
            color: #e9d5ff;
            font-size: 1.05rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
            text-align: left;
            border: 2px solid transparent;
        }

        .menu-item:hover {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            font-weight: 700;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            background: transparent;
        }

        .main-section {
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 8px 20px rgba(91, 33, 182, 0.3);
        }

        .main-section.hidden {
            display: none;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .section-description {
            font-size: 1rem;
            color: #e9d5ff;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .field-card {
            background: rgba(107, 33, 168, 0.4);
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 2px solid rgba(251, 191, 36, 0.3);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .field-card:hover {
            border-color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
            background: rgba(107, 33, 168, 0.6);
        }

        .field-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .field-label {
            font-size: 0.9rem;
            color: #c4b5fd;
            font-weight: 600;
        }

        .field-value {
            font-size: 1.125rem;
            color: #fbbf24;
            font-weight: 700;
        }

        .edit-btn {
            padding: 0.5rem 1.5rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .add-method-btn {
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
            margin-bottom: 2rem;
        }

        .add-method-btn:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.5);
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(251, 191, 36, 0.3);
            position: relative;
        }

        .modal-header {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 1.5rem;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(239, 68, 68, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: #ef4444;
            transform: scale(1.1);
        }

        .modal input,
        .modal select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(251, 191, 36, 0.3);
            border-radius: 0.5rem;
            font-size: 1rem;
            font-family: inherit;
            color: white;
            background: rgba(76, 29, 149, 0.6);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .modal input:focus,
        .modal select:focus {
            outline: none;
            border-color: #fbbf24;
            background: rgba(76, 29, 149, 0.8);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.2);
        }

        .modal input::placeholder {
            color: #c4b5fd;
        }

        .save-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.5);
        }

        .payment-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .skill-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .skill-item {
            background: rgba(107, 33, 168, 0.4);
            border: 2px solid rgba(251, 191, 36, 0.3);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .skill-item:hover {
            border-color: #fbbf24;
            background: rgba(107, 33, 168, 0.6);
        }

        .skill-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fbbf24;
        }

        .skill-level {
            font-size: 0.95rem;
            color: #e9d5ff;
            margin-top: 0.25rem;
        }

        .skill-actions {
            display: flex;
            gap: 0.5rem;
        }

        .icon-btn {
            padding: 0.5rem;
            background: rgba(251, 191, 36, 0.2);
            border: none;
            border-radius: 0.5rem;
            color: #fbbf24;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover {
            background: rgba(251, 191, 36, 0.4);
            transform: scale(1.1);
        }

        .icon-btn.delete:hover {
            background: rgba(239, 68, 68, 0.4);
            color: #ef4444;
        }

        .hidden {
            display: none;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
            z-index: 100;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1.5rem;
                flex-direction: row;
                gap: 1.5rem;
                overflow-x: auto;
            }

            .profile-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 200px;
                flex-shrink: 0;
            }

            .profile-photo {
                width: 80px;
                height: 80px;
            }

            .profile-name {
                font-size: 1.125rem;
            }

            .profile-email {
                font-size: 0.875rem;
            }

            .menu-card {
                display: flex;
                flex-direction: row;
                gap: 0.5rem;
                flex-wrap: nowrap;
                overflow-x: auto;
            }

            .menu-item {
                white-space: nowrap;
                min-width: 150px;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .nav-button {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }

            .page-header {
                padding: 1.5rem 1rem;
            }

            .page-header-content {
                flex-direction: column;
                gap: 1rem;
                padding: 0;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .content {
                padding: 1rem;
            }

            .main-section {
                padding: 1.5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .field-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .edit-btn {
                width: 100%;
            }

            .payment-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 280px;
                z-index: 200;
                transition: left 0.3s ease;
                flex-direction: column;
                padding: 2rem 1rem;
                overflow-y: auto;
                overflow-x: hidden;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar .profile-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: auto;
                width: 100%;
            }

            .sidebar .menu-card {
                flex-direction: column;
                width: 100%;
            }

            .sidebar .menu-item {
                width: 100%;
                text-align: left;
                min-width: auto;
            }

            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-content {
                padding: 2rem 1.5rem;
            }

            /* Overlay when mobile menu is open */
            .sidebar.active::before {
                content: '';
                position: fixed;
                top: 0;
                left: 280px;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 0 0.5rem;
            }

            .nav-button {
                padding: 0.75rem 0.75rem;
                font-size: 0.9rem;
            }

            .sign-out-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .page-header {
                padding: 1rem;
            }

            .main-section {
                padding: 1rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .section-description {
                font-size: 0.9rem;
            }
        }
    </style>