<?php

require_once __DIR__ . '/../../config/database.php';

class ReportService {
    private $db;

    public function __construct() {
        $this->db = DatabaseConfig::getInstance();
    }

    public function getOrderSummary() {
        $data = $this->db->fetchAll("
            SELECT
                status,
                COUNT(*) as count,
                SUM(total) as total_amount,
                AVG(total) as avg_amount
            FROM orders
            GROUP BY status
            ORDER BY
                CASE status
                    WHEN 'draft' THEN 1
                    WHEN 'pending' THEN 2
                    WHEN 'paid' THEN 3
                    WHEN 'shipped' THEN 4
                    WHEN 'delivered' THEN 5
                    WHEN 'closed' THEN 6
                    ELSE 7
                END
        ");

        return [
            'type' => 'table',
            'title' => 'Order Summary by Status',
            'data' => $data,
            'columns' => ['Status', 'Count', 'Total Amount', 'Average Amount']
        ];
    }

    public function getOrderTrends($days = 30) {
        $data = $this->db->fetchAll("
            SELECT
                DATE(created_at) as date,
                COUNT(*) as orders,
                SUM(total) as revenue
            FROM orders
            WHERE created_at >= datetime('now', '-{$days} days')
            GROUP BY DATE(created_at)
            ORDER BY date
        ");

        return [
            'type' => 'chart',
            'title' => "Order Trends (Last {$days} days)",
            'data' => $data,
            'chart_type' => 'line'
        ];
    }

    public function getTopCustomers($limit = 10) {
        $data = $this->db->fetchAll("
            SELECT
                customer,
                COUNT(*) as order_count,
                SUM(total) as total_spent,
                AVG(total) as avg_order_value
            FROM orders
            WHERE status NOT IN ('cancelled', 'refunded')
            GROUP BY customer
            ORDER BY total_spent DESC
            LIMIT ?
        ", [$limit]);

        return [
            'type' => 'table',
            'title' => "Top {$limit} Customers",
            'data' => $data,
            'columns' => ['Customer', 'Orders', 'Total Spent', 'Avg Order Value']
        ];
    }

    public function getProcessEfficiency() {
        $data = $this->db->fetchAll("
            SELECT
                p1.to_state as current_state,
                AVG(julianday(p2.created_at) - julianday(p1.created_at)) * 24 as avg_hours_in_state,
                COUNT(*) as transitions
            FROM process_history p1
            LEFT JOIN process_history p2 ON p1.entity_id = p2.entity_id
                AND p1.process_name = p2.process_name
                AND p2.id = (
                    SELECT MIN(id) FROM process_history p3
                    WHERE p3.entity_id = p1.entity_id
                    AND p3.process_name = p1.process_name
                    AND p3.id > p1.id
                )
            WHERE p1.process_name = 'order'
            GROUP BY p1.to_state
            HAVING COUNT(*) > 0
        ");

        return [
            'type' => 'table',
            'title' => 'Process Efficiency (Average time in each state)',
            'data' => $data,
            'columns' => ['State', 'Avg Hours', 'Transitions']
        ];
    }

    public function getRevenueBreakdown() {
        $data = $this->db->fetchAll("
            SELECT
                strftime('%Y-%m', created_at) as month,
                SUM(CASE WHEN status = 'delivered' OR status = 'closed' THEN total ELSE 0 END) as completed_revenue,
                SUM(CASE WHEN status = 'paid' OR status = 'shipped' THEN total ELSE 0 END) as in_progress_revenue,
                SUM(CASE WHEN status = 'pending' THEN total ELSE 0 END) as pending_revenue,
                COUNT(*) as total_orders
            FROM orders
            WHERE created_at >= datetime('now', '-12 months')
            GROUP BY strftime('%Y-%m', created_at)
            ORDER BY month DESC
        ");

        return [
            'type' => 'chart',
            'title' => 'Revenue Breakdown by Month',
            'data' => $data,
            'chart_type' => 'stacked_bar'
        ];
    }

    public function generateReport($reportType, $params = []) {
        switch ($reportType) {
            case 'order_summary':
                return $this->getOrderSummary();

            case 'order_trends':
                $days = $params['days'] ?? 30;
                return $this->getOrderTrends($days);

            case 'top_customers':
                $limit = $params['limit'] ?? 10;
                return $this->getTopCustomers($limit);

            case 'process_efficiency':
                return $this->getProcessEfficiency();

            case 'revenue_breakdown':
                return $this->getRevenueBreakdown();

            default:
                throw new Exception("Unknown report type: {$reportType}");
        }
    }

    public function getAvailableReports() {
        return [
            'order_summary' => 'Order Summary by Status',
            'order_trends' => 'Order Trends',
            'top_customers' => 'Top Customers',
            'process_efficiency' => 'Process Efficiency',
            'revenue_breakdown' => 'Revenue Breakdown'
        ];
    }
}