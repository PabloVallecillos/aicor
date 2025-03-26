export interface ApiLoginResponse {
  access_token: string;
  token_type: string;
  expires_in: number;
}

export interface ApiLoginError {
  message: string;
}

export interface ApiListResponse<T> {
  data: PaginationResponse<T>;
}

export interface RequestListEndpoint {
  only_fields?: string[];
  hide_fields?: string[];
  filters?: string[];
  group?: string[];
  order?: string[];
  per_page?: number;
  link_range?: number;
  page?: number;
  paginator_mode?: number;
}

export interface RequestCartAddMultipleEndpoint {
  items: {
    product_id: number;
    quantity: number;
  }[];
}

export interface PaginationResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

export interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}
